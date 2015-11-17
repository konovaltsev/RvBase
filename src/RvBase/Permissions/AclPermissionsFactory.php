<?php

namespace RvBase\Permissions;

use RvBase\Permissions\Acl\IdentityRoleInitializerInterface;
use RvBase\Permissions\Acl\ResourceInitializerInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Класс AclPermissionsFactory
 *
 * @package RvBase\Permissions
 */
class AclPermissionsFactory implements FactoryInterface
{
    private $permissionsConfig;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AclPermissions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->getPermissionsConfig($serviceLocator);

        return new AclPermissions(
            function() use($serviceLocator){ return $this->getAcl($serviceLocator); },
            $this->getAuthenticationService($serviceLocator),
            $this->getIdentityRoleInitializer($serviceLocator),
            $this->getResourceInitializer($serviceLocator)
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Acl
     */
    protected function getAcl(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->permissionsConfig['acl']['acl']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationServiceInterface
     */
    protected function getAuthenticationService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->permissionsConfig['authentication_service']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityRoleInitializerInterface
     */
    protected function getIdentityRoleInitializer(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->permissionsConfig['acl']['identity_role_initializer']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ResourceInitializerInterface
     */
    protected function getResourceInitializer(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->permissionsConfig['acl']['resource_initializer']);
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
