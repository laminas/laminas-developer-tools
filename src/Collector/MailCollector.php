<?php

/**
 * @see       https://github.com/laminas/laminas-developer-tools for the canonical source repository
 * @copyright https://github.com/laminas/laminas-developer-tools/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-developer-tools/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DeveloperTools\Collector;

use Laminas\Mvc\MvcEvent;

/**
 * Mail Data Collector.
 *
 */
class MailCollector extends AbstractCollector implements AutoHideInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mail';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 100;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(MvcEvent $mvcEvent)
    {
        // todo
    }

    /**
     * {@inheritdoc}
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
