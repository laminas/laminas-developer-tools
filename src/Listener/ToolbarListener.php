<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Collector\AutoHideInterface;
use Laminas\DeveloperTools\Exception\InvalidOptionException;
use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\Profiler;
use Laminas\DeveloperTools\ProfilerEvent;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Model\ViewModel;

use function array_keys;
use function explode;
use function extension_loaded;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function is_dir;
use function phpversion;
use function preg_match;
use function preg_replace;
use function sprintf;
use function str_contains;
use function str_replace;
use function stripos;
use function time;

/**
 * Developer Toolbar Listener
 */
class ToolbarListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * Time to live for the version cache in seconds.
     *
     * @var integer
     */
    public const VERSION_CACHE_TTL = 3600;

    /**
     * Documentation URI pattern.
     *
     * @var string
     */
    public const DOC_URI = 'https://docs.laminas.dev/';

    /** @var Options */
    protected $options;

    /**
     * @param object $renderer
     */
    public function __construct(protected $renderer, Options $options)
    {
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->getSharedManager()->attach(
            'profiler',
            ProfilerEvent::EVENT_COLLECTED,
            [$this, 'onCollected'],
            Profiler::PRIORITY_TOOLBAR
        );
    }

    /**
     * ProfilerEvent::EVENT_COLLECTED event callback.
     */
    public function onCollected(ProfilerEvent $event)
    {
        $application = $event->getApplication();
        $request     = $application->getRequest();

        if ($request->isXmlHttpRequest()) {
            return;
        }

        $response = $application->getResponse();
        $headers  = $response->getHeaders();
        if (
            $headers->has('Content-Type')
            && ! str_contains($headers->get('Content-Type')->getFieldValue(), 'html')
        ) {
            return;
        }

        // todo: X-Debug-Token logic?
        // todo: redirect logic

        $this->injectToolbar($event);
    }

    /**
     * Tries to injects the toolbar into the view. The toolbar is only injected in well
     * formed HTML by replacing the closing body tag, leaving ESI untouched.
     */
    protected function injectToolbar(ProfilerEvent $event)
    {
        $entries  = $this->renderEntries($event);
        $response = $event->getApplication()->getResponse();

        $toolbarView = new ViewModel(['entries' => $entries]);
        $toolbarView->setTemplate('laminas-developer-tools/toolbar/toolbar');
        $toolbar = $this->renderer->render($toolbarView);

        $toolbarCss = new ViewModel([
            'position' => $this->options->getToolbarPosition(),
        ]);
        $toolbarCss->setTemplate('laminas-developer-tools/toolbar/style');
        $style = $this->renderer->render($toolbarCss);

        $toolbarJs = new ViewModel();
        $toolbarJs->setTemplate('laminas-developer-tools/toolbar/script');
        $script = $this->renderer->render($toolbarJs);

        $toolbar = str_replace(['$', '\\\\'], ['\$', '\\\\\\'], $toolbar);

        $content = $response->getBody();
        $isHtml5 = stripos($content, '<!doctype html>') === 0;

        if (preg_match('/<\/body>(?![\s\S]*<\/body>)/i', $content)) {
            $injected = preg_replace(
                '/<\/body>(?![\s\S]*<\/body>)/i',
                $toolbar . $script . "\n</body>",
                $content,
                1
            );

            $prepend = $isHtml5
                ? (preg_match('/<\/head>/i', $injected) ? 'head' : 'body')
                : 'body';

            $injected = preg_replace('/<\/' . $prepend . '>/i', $style . "\n</$prepend>", $injected, 1);
        } else {
            $injected = $isHtml5
                ? (
                    stripos($content, '</html>') !== false
                        ? preg_replace('/<\/html>/i', $style . $toolbar . $script . "\n</html>", $content, 1)
                        : '<!doctype html>' . $content . $style . $toolbar . $script
                )
                : $content;
        }

        $response->setContent($injected);
    }

    /**
     * Renders all toolbar entries.
     *
     * @return array
     * @throws InvalidOptionException
     */
    protected function renderEntries(ProfilerEvent $event)
    {
        $entries      = [];
        $report       = $event->getReport();
        $laminasEntry = new ViewModel([
            'php_version' => phpversion(),
            'has_intl'    => extension_loaded('intl'),
            'doc_uri'     => self::DOC_URI,
            'modules'     => $this->getModules($event),
        ]);
        $laminasEntry->setTemplate('laminas-developer-tools/toolbar/laminas');

        $entries[]  = $this->renderer->render($laminasEntry);
        $errors     = [];
        $collectors = $this->options->getCollectors();
        $templates  = $this->options->getToolbarEntries();

        foreach ($templates as $name => $template) {
            if (isset($collectors[$name])) {
                try {
                    $collectorInstance = $report->getCollector($name);

                    if (
                        $this->options->getToolbarAutoHide()
                        && $collectorInstance instanceof AutoHideInterface
                        && $collectorInstance->canHide()
                    ) {
                        continue;
                    }

                    $collector = new ViewModel([
                        'report'    => $report,
                        'collector' => $collectorInstance,
                    ]);
                    $collector->setTemplate($template);
                    $entries[] = $this->renderer->render($collector);
                } catch (RuntimeException) {
                    $errors[$name] = $template;
                }
            }
        }

        if (! empty($errors) || $report->hasErrors()) {
            $tmp = [];
            foreach ($errors as $name => $template) {
                $cur   = sprintf('Unable to render toolbar template %s (%s).', $name, $template);
                $tmp[] = $cur;
                $report->addError($cur);
            }

            if ($this->options->isStrict()) {
                throw new InvalidOptionException(implode(' ', $tmp));
            }
        }

        if ($report->hasErrors()) {
            $errorTpl = new ViewModel(['errors' => $report->getErrors()]);
            $errorTpl->setTemplate('laminas-developer-tools/toolbar/error');
            $entries[] = $this->renderer->render($errorTpl);
        }

        return $entries;
    }

    /**
     * Wrapper for Zend\Version::getLatest with caching functionality, so that
     * LaminasDeveloperTools won't act as a "DDoS bot-network".
     *
     * @param  string $currentVersion
     * @return array
     */
    protected function getLatestVersion($currentVersion)
    {
        if (! $this->options->isVersionCheckEnabled()) {
            return [true, ''];
        }

        $cacheDir = $this->options->getCacheDir();

        // exit early if the cache dir doesn't exist,
        // to prevent hitting the GitHub API for every request.
        if (! is_dir($cacheDir)) {
            return [true, ''];
        }

        if (file_exists($cacheDir . '/Laminas_Developer_Tool_Version.cache')) {
            $cache = file_get_contents($cacheDir . '/Laminas_Developer_Tool_Version.cache');
            $cache = explode('|', $cache);

            if ($cache[0] + self::VERSION_CACHE_TTL > time()) {
                // the cache file was written before the version was upgraded.
                if ($currentVersion === $cache[2] || $cache[2] === 'N/A') {
                    return [true, ''];
                }

                return [
                    $cache[1] === 'yes' ? true : false,
                    $cache[2],
                ];
            }
        }

        $isLatest = Version::isLatest();
        $latest   = Version::getLatest();

        file_put_contents(
            $cacheDir . '/Laminas_Developer_Tool_Version.cache',
            sprintf(
                '%d|%s|%s',
                time(),
                $isLatest ? 'yes' : 'no',
                $latest ?? 'N/A'
            )
        );

        return [$isLatest, $latest];
    }

    private function getModules(ProfilerEvent $event)
    {
        if (! $application = $event->getApplication()) {
            return;
        }

        $serviceManager = $application->getServiceManager();
        $moduleManager  = $serviceManager->get('ModuleManager');

        return array_keys($moduleManager->getLoadedModules());
    }
}
