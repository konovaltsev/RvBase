<?php

namespace RvBase\Mvc\Service;
use RvBase\Mvc\DispatchListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DispatchListenerFactory
 * @package RvBase\Mvc\Service
 */
class DispatchListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $dispatchListener = new DispatchListener();

        if(isset($config['rv-base']['acl']))
        {
            if(isset($config['rv-base']['acl']['default_acl']))
            {
                $dispatchListener->setAcl($serviceLocator->get('acl'));
            }

            if(isset($config['rv-base']['acl']['default_role']))
            {
                $dispatchListener->setRole($config['rv-base']['acl']['default_role']);
            }
        }

        return $dispatchListener;
    }
}
