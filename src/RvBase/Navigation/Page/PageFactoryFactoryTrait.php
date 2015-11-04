<?php

namespace RvBase\Navigation\Page;

use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractPageFactoryFactory
 * @package RvBase\Navigation\Page
 */
trait PageFactoryFactoryTrait
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return null|\Zend\Mvc\Router\RouteMatch
     */
    protected function getRouteMatch(ServiceLocatorInterface $serviceLocator)
    {
        return $this->getMvcEvent($serviceLocator)->getRouteMatch();
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Mvc\Router\RouteStackInterface
     */
    protected function getRouter(ServiceLocatorInterface $serviceLocator)
    {
        return $this->getMvcEvent($serviceLocator)->getRouter();
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Stdlib\RequestInterface
     */
    protected function getRequest(ServiceLocatorInterface $serviceLocator)
    {
        return $this->getMvcEvent($serviceLocator)->getRequest();
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Mvc\MvcEvent
     */
    private function getMvcEvent(ServiceLocatorInterface $serviceLocator)
    {
        return $this->getApplication($serviceLocator)->getMvcEvent();
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Application
     */
    private function getApplication(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('Application');
    }
}
