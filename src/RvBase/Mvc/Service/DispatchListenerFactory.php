<?php

namespace RvBase\Mvc\Service;

use RvBase\Mvc\DispatchListener;
use RvBase\ServiceProvider\PermissionsServiceProviderTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DispatchListenerFactory
 * @package RvBase\Mvc\Service
 */
class DispatchListenerFactory implements FactoryInterface
{
    use PermissionsServiceProviderTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dispatchListener = new DispatchListener(
            $this->getPermissionsService($serviceLocator)
        );

        return $dispatchListener;
    }
}
