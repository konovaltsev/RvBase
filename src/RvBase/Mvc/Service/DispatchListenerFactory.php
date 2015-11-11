<?php

namespace RvBase\Mvc\Service;

use RvBase\Mvc\DispatchListener;
use RvBase\Permissions\PermissionsInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DispatchListenerFactory
 * @package RvBase\Mvc\Service
 */
class DispatchListenerFactory implements FactoryInterface
{
    private $permissionsConfig;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dispatchListener = new DispatchListener(
            $this->getPermissions($serviceLocator)
        );

        return $dispatchListener;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PermissionsInterface
     */
    private function getPermissions(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->getPermissionsConfig($serviceLocator)['service']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    private function getPermissionsConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->permissionsConfig === null) {
            $this->permissionsConfig = $serviceLocator->get('Config')['rv-base']['permissions'];
        }

        return $this->permissionsConfig;
    }
}
