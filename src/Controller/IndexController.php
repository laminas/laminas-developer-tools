<?php

declare(strict_types=1);

namespace Laminas\DeveloperTools\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @inheritDoc
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
