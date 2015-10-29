<?php

namespace RvBase;

use RvBase\Mvc\DispatchListener;
use RvBase\Mvc\View\Http\ViewManager;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package RvBase
 */
class Module
    implements
        Feature\ConfigProviderInterface,
        Feature\BootstrapListenerInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\MvcEvent $e */
        /** @var $application \Zend\Mvc\Application */
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $applicationEventManager = $application->getEventManager();

        $config = $serviceManager->get('Config');
        if($config['rv-base']['view-manager']['enabled'] === true)
        {
            $rvViewManager = $this->getViewManager($serviceManager);
            if($rvViewManager)
            {
                $rvViewManager->onBootstrap($e);
            }
        }

        if($config['rv-base']['dispatch-listener']['enabled'] === true)
        {
            $applicationEventManager->attachAggregate($this->getDispatchListener($serviceManager));
        }

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

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ViewManager
     */
    private function getViewManager(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator->has('rv-base.view-manager'))
        {
            return $serviceLocator->get('rv-base.view-manager');
        }

        return null;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return DispatchListener
     */
    private function getDispatchListener(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('rv-base.dispatch-listener');
    }
}
