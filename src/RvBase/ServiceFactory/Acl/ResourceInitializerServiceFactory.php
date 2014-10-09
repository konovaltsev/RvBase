<?php

namespace RvBase\ServiceFactory\Acl;

use RvBase\Permissions\Acl\Resource\ResourceProviderInterface;
use RvBase\Permissions\Acl\ResourceInitializer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceInitializerServiceFactory
 * @package RvBase\ServiceFactory\Acl
 */
class ResourceInitializerServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ResourceInitializer(
            $this->getResourceProvider($serviceLocator)
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ResourceProviderInterface
     */
    protected function getResourceProvider(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('rv-base.permissions.acl.resource-provider');
    }
}
