<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Collector;

use Laminas\Mvc\MvcEvent;

/**
 * Mail Data Collector.
 */
class MailCollector extends AbstractCollector implements AutoHideInterface
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'mail';
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 100;
    }

    /**
     * @inheritDoc
     */
    public function collect(MvcEvent $mvcEvent)
    {
        // todo
    }

    /**
     * @inheritDoc
     */
    public function canHide()
    {
        return true;
    }

    /**
     * Returns the total number of E-Mails send.
     *
     * @return integer
     */
    public function getMailsSend()
    {
        return 0;
    }
}
