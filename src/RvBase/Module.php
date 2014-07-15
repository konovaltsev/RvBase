<?php

namespace RvBase;

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
        if($config['rv-base']['view-manager']['enabled'] === true && $serviceManager->has('rv-base.view-manager'))
        {
            /** @var \RvBase\Mvc\View\Http\ViewManager $rvViewManager */
            $rvViewManager = $serviceManager->get('rv-base.view-manager');
            if($rvViewManager)
            {
                $rvViewManager->onBootstrap($e);
            }
        }

        if($config['rv-base']['dispatch-listener']['enabled'] === true)
        {
            /** @var \RvBase\Mvc\View\Http\ViewManager $rvViewManager */
            $applicationEventManager->attachAggregate($serviceManager->get('rv-base.dispatch-listener'));
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
}
