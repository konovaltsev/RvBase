<?php

namespace RvBase;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package RvBase
 */
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
//        $eventManager        = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();
//        $moduleRouteListener->attach($eventManager);

        $serviceManager = $e->getTarget()->getServiceManager();
        $this->initNavigationPageFactories($serviceManager);
    }

    protected function initNavigationPageFactories(ServiceLocatorInterface $serviceLocator)
    {
        AbstractPage::addFactory(
            function($options) use($serviceLocator)
            {
                if(!isset($options['page-factory']))
                {
                    return null;
                }
                /** @var \RvBase\Navigation\Page\PageFactoryInterface $pageFactory */
                $pageFactory = $serviceLocator->get($options['page-factory']);
                $options['page-factory'] = null;
                return $pageFactory->createPage($options);
            }
        );
    }
}
