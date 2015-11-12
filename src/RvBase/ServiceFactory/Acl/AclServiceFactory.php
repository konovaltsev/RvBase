<?php

namespace RvBase\ServiceFactory\Acl;

use RvBase\Permissions\Acl\IdentityRoleInitializerInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclServiceFactory
 * @package RvBase\Permissions\Acl
 */
class AclServiceFactory implements FactoryInterface
{
    protected $config;
    protected $permissionsConfig;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->config = null;

        $acl = $this->createAcl();

        $this->initRoles($serviceLocator, $acl);
        $this->initIdentityRole($serviceLocator, $acl);
        $this->initResources($serviceLocator, $acl);
        $this->initRules($serviceLocator, $acl);

        return $acl;
    }

    protected function createAcl()
    {
        return new Acl();
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!is_array($this->config)) {
            if (!$serviceLocator->has('Config')) {
                $this->config = [];

                return $this->config;
            }

            $config = $serviceLocator->get('Config');
            if (!isset($config['rv-base']['permissions']) || !is_array($config['rv-base']['permissions'])) {
                $this->config = [];

                return $this->config;
            }

            $this->config = $config['rv-base']['permissions'];
        }

        return $this->config;
    }

    protected function initRoles(ServiceLocatorInterface $serviceLocator, Acl $acl)
    {
        $config = $this->getConfig($serviceLocator)['acl'];
        if (!isset($config['initializers']['roles'])) {
            return;
        }

        array_walk(
            $config['initializers']['roles'],
            function ($initializerClass) use ($acl, $serviceLocator) {
                /** @var InitializerInterface $initializer */
                $initializer = new $initializerClass();
                $initializer->initialize($acl, $serviceLocator);
            }
        );
    }

    protected function initIdentityRole(ServiceLocatorInterface $serviceLocator, Acl $acl)
    {
        $config = $this->getConfig($serviceLocator)['acl'];
        if (!isset($config['init_authenticated_identity_role']) || !$config['init_authenticated_identity_role']) {
            return;
        }

        $identityRoleInitializer = $this->getIdentityRoleInitializer($serviceLocator);

        $authenticationService = $this->getAuthentication($serviceLocator);
        $identity              = $authenticationService->hasIdentity() ? $authenticationService->getIdentity() : null;
        $identityRoleInitializer->initialize($acl, $identity);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityRoleInitializerInterface
     */
    protected function getIdentityRoleInitializer(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator)['acl'];

        return $serviceLocator->get($config['identity_role_initializer']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationServiceInterface
     */
    protected function getAuthentication(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);

        return $serviceLocator->get($config['authentication_service']);
    }

    protected function initResources(ServiceLocatorInterface $serviceLocator, Acl $acl)
    {
        $config = $this->getConfig($serviceLocator)['acl'];
        if (!isset($config['initializers']['resources'])) {
            return;
        }

        array_walk(
            $config['initializers']['resources'],
            function ($initializerClass) use ($acl, $serviceLocator) {
                /** @var InitializerInterface $initializer */
                $initializer = new $initializerClass();
                $initializer->initialize($acl, $serviceLocator);
            }
        );
    }

    protected function initRules(ServiceLocatorInterface $serviceLocator, Acl $acl)
    {
        $config = $this->getConfig($serviceLocator)['acl'];
        if (!isset($config['initializers']['rules'])) {
            return;
        }

        array_walk(
            $config['initializers']['rules'],
            function ($initializerClass) use ($acl, $serviceLocator) {
                /** @var InitializerInterface $initializer */
                $initializer = new $initializerClass();
                $initializer->initialize($acl, $serviceLocator);
            }
        );
    }
}
