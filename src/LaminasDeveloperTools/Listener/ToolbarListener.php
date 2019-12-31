<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Listener;

use Laminas\DeveloperTools\Exception\InvalidOptionException;
use Laminas\DeveloperTools\Options;
use Laminas\DeveloperTools\Profiler;
use Laminas\DeveloperTools\ProfilerEvent;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Model\ViewModel;
use Zend\Version\Version;

/**
 * Developer Toolbar Listener
 *
 * @category   Laminas
 * @package    LaminasDeveloperTools
 * @subpackage Listener
 * @copyright  Copyright (c) 2005-2012 Laminas (https://www.zend.com)
 * @license    https://getlaminas.org/license/new-bsd     New BSD License
 */
class ToolbarListener implements ListenerAggregateInterface
{
    /**
     * Time to live for the version cache in seconds.
     *
     * @var integer
     */
    const VERSION_CACHE_TTL = 3600;

    /**
     * Dev documentation URI pattern.
     *
     * @var string
     */
    const DEV_DOC_URI_PATTERN = 'http://laminas.readthedocs.org/en/%s/index.html';

    /**
     * Documentation URI pattern.
     *
     * @var string
     */
    const DOC_URI_PATTERN = 'https://getlaminas.org/manual/%s/en/index.html';

    /**
     * @var object
     */
    protected $renderer;

    /**
     * @var Options
     */
    protected $options;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * Constructor.
     *
     * @param object  $viewRenderer
     * @param Options $options
     */
    public function __construct($viewRenderer, Options $options)
    {
        $this->options  = $options;
        $this->renderer = $viewRenderer;
    }

    /**
     * @inheritdoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            ProfilerEvent::EVENT_COLLECTED,
            array($this, 'onCollected'),
            Profiler::PRIORITY_TOOLBAR
        );
    }

    /**
     * @inheritdoc
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * ProfilerEvent::EVENT_COLLECTED event callback.
     *
     * @param ProfilerEvent $event
     */
    public function onCollected(ProfilerEvent $event)
    {
        $application = $event->getApplication();
        $request     = $application->getRequest();

        if ($request->isXmlHttpRequest()) {
            return;
        }

        $response = $application->getResponse();
        $headers = $response->getHeaders();
        if ($headers->has('Content-Type')
            && false !== strpos($headers->get('Content-Type')->getFieldValue(), 'html')
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
     *
     * @param ProfilerEvent $event
     */
    protected function injectToolbar(ProfilerEvent $event)
    {
        $entries     = $this->renderEntries($event);
        $response    = $event->getApplication()->getResponse();;

        $toolbarView = new ViewModel(array('entries' => $entries));
        $toolbarView->setTemplate('laminas-developer-tools/toolbar/toolbar');
        $toolbar     = $this->renderer->render($toolbarView);
        $toolbar     = str_replace("\n", '', $toolbar);

        $toolbarCss  = new ViewModel(array(
            'position' => $this->options->getToolbarPosition(),
        ));
        $toolbarCss->setTemplate('laminas-developer-tools/toolbar/style');
        $style       = $this->renderer->render($toolbarCss);
        $style       = str_replace(array("\n", '  '), '', $style);

        $injected    = preg_replace('/<\/body>/i', $toolbar . "\n</body>", $response->getBody(), 1);
        $injected    = preg_replace('/<\/head>/i', $style . "\n</head>", $injected, 1);

        $response->setContent($injected);
    }

    /**
     * Renders all toolbar entries.
     *
     * @param  ProfilerEvent $event
     * @return array
     * @throws InvalidOptionException
     */
    protected function renderEntries(ProfilerEvent $event)
    {
        $entries = array();
        $report  = $event->getReport();

        list($isLatest, $latest) = $this->getLatestVersion(Version::VERSION);
        
        if (false === ($pos = strpos(Version::VERSION, 'dev'))) {
            $docUri = sprintf(self::DOC_URI_PATTERN, substr(Version::VERSION, 0, 3));
        } else { // unreleased dev branch - compare minor part of versions
            $partsCurrent       = explode('.', substr(Version::VERSION, 0, $pos));
            $partsLatestRelease = explode('.', $latest);
            $docUri             = sprintf(
                self::DEV_DOC_URI_PATTERN,
                $partsLatestRelease[1] == $partsCurrent[1] ? 'latest' : 'develop'
            );
        }

        $laminasEntry = new ViewModel(array(
            'laminas_version'  => Version::VERSION,
            'is_latest'   => $isLatest,
            'latest'      => $latest,
            'php_version' => phpversion(),
            'has_intl'    => extension_loaded('intl'),
            'doc_uri'     => $docUri,
        ));
        $laminasEntry->setTemplate('laminas-developer-tools/toolbar/laminas');

        $entries[]  = $this->renderer->render($laminasEntry);
        $errors     = array();
        $collectors = $this->options->getCollectors();
        $templates  = $this->options->getToolbarEntries();

        foreach ($templates as $name => $template) {
            if (isset($collectors[$name])) {
                try {
                    $collector = new ViewModel(array(
                        'report'    => $report,
                        'collector' => $report->getCollector($name),
                    ));
                    $collector->setTemplate($template);
                    $entries[] = $this->renderer->render($collector);
                } catch (RuntimeException $e) {
                    $errors[$name] = $template;
                }
            }
        }

        if (!empty($errors) || $report->hasErrors()) {
            $tmp = array();
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
            $errorTpl  = new ViewModel(array('errors' => $report->getErrors()));
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
        if (!$this->options->isVersionCheckEnabled()) {
            return array(true, '');
        }

        $cacheDir = $this->options->getCacheDir();

        // exit early if the cache dir doesn't exist,
        // to prevent hitting the GitHub API for every request.
        if (!is_dir($cacheDir)) {
            return array(true, '');
        }

        if (file_exists($cacheDir . '/Laminas_Developer_Tool_Version.cache')) {
            $cache = file_get_contents($cacheDir . '/Laminas_Developer_Tool_Version.cache');
            $cache = explode('|', $cache);

            if ($cache[0] + self::VERSION_CACHE_TTL > time()) {
                // the cache file was written before the version was upgraded.
                if ($currentVersion === $cache[2] || $cache[2] === 'N/A') {
                    return array(true, '');
                }

                return array(
                    ($cache[1] === 'yes') ? true : false,
                    $cache[2]
                );
            }
        }

        $isLatest = Version::isLatest();
        $latest   = Version::getLatest();

        file_put_contents(
            $cacheDir . '/Laminas_Developer_Tool_Version.cache',
            sprintf(
                '%d|%s|%s',
                time(),
                ($isLatest) ? 'yes' : 'no',
                ($latest === null) ? 'N/A' : $latest
            )
        );

        return array($isLatest, $latest);
    }
}
