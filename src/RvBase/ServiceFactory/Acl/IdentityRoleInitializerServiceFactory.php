<?php

namespace RvBase\ServiceFactory\Acl;

use RvBase\Permissions\Acl\IdentityRoleInitializer;
use RvBase\Permissions\Acl\Role\IdentityRoleParentsProviderInterface;
use RvBase\Permissions\Acl\Role\IdentityRoleProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IdentityRoleInitializerServiceFactory
 * @package RvBase\ServiceFactory\Acl
 */
class IdentityRoleInitializerServiceFactory implements FactoryInterface
{
    protected $config;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->config = null;

        $identityRoleInitializer = new IdentityRoleInitializer(
            $this->getIdentityRoleProvider($serviceLocator),
            $this->getIdentityRoleParentsProvider($serviceLocator)
        );

        return $identityRoleInitializer;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityRoleProviderInterface
     */
    protected function getIdentityRoleProvider(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        return $serviceLocator->get($config['identity_role_provider']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityRoleParentsProviderInterface
     */
    protected function getIdentityRoleParentsProvider(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        return $serviceLocator->get($config['identity_parent_roles_provider']);
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if(!is_array($this->config))
        {
            if (!$serviceLocator->has('Config'))
            {
                $this->config = array();
                return $this->config;
            }

            $config = $serviceLocator->get('Config');
            if(!isset($config['rv-base']['permissions']['acl']) || !is_array($config['rv-base']['permissions']['acl']))
            {
                $this->config = array();
                return $this->config;
            }

            $this->config = $config['rv-base']['permissions']['acl'];
        }

        return $this->config;
    }
}
