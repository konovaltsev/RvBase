<?php

namespace RvBase\Mvc\Service;

use RvBase\Mvc\View\Http\ViewManager as HttpViewManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class HttpViewManagerFactory
 * @package RvBase\Mvc\Service
 */
class HttpViewManagerFactory implements FactoryInterface
{
    /**
     * Create and return a view manager for the HTTP environment
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return HttpViewManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new HttpViewManager();
    }
}
