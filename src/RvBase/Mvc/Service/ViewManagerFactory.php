<?php

namespace RvBase\Mvc\Service;

use Zend\Console\Console;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use RvBase\Mvc\View\Console\ViewManager as ConsoleViewManager;
use RvBase\Mvc\View\Http\ViewManager as HttpViewManager;

/**
 * Class ViewManagerFactory
 * @package RvBase\Mvc\Service
 */
class ViewManagerFactory implements FactoryInterface
{
    /**
     * Create and return a view manager based on detected environment
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ConsoleViewManager|HttpViewManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (Console::isConsole()) { //TODO:
            return false;
//            return $serviceLocator->get('rv-base.console-view-manager');
        }

        return $serviceLocator->get('rv-base.http-view-manager');
    }
}
