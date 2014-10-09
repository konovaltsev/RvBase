<?php

namespace RvBase\ServiceFactory\Acl;

use RvBase\Permissions\Acl\Role\IdentityRoleParentsProviderChain;
use RvBase\Permissions\Acl\Role\IdentityRoleParentsProviderInterface;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IdentityRoleParentsProviderFactory
 * @package RvBase\ServiceFactory\Acl
 */
class IdentityRoleParentsProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        $providersChain = new IdentityRoleParentsProviderChain();
        array_walk(
            $config,
            function($options) use($serviceLocator, $providersChain)
            {
                $priority = isset($options['priority'])? $options['priority'] : 1;
                $provider = null;
                switch(true)
                {
                    case isset($options['class']):
                        $class = $options['class'];
                        $provider = new $class();
                        break;
                    case isset($options['service']):
                        $provider = $serviceLocator->get($options['service']);
                        break;
                }

                if(!$provider instanceof IdentityRoleParentsProviderInterface)
                {
                    throw new Exception\RuntimeException(__METHOD__ . ': Parent roles provider not instance of IdentityRoleParentsProviderInterface');
                }

                $providersChain->addParentsProvider($provider, $priority);
            }
        );

        return $providersChain;
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config'))
        {
            return array();
        }

        $config = $serviceLocator->get('Config');
        if(!isset($config['rv-base']['permissions']['acl']['parent_roles_providers']) || !is_array($config['rv-base']['permissions']['acl']['parent_roles_providers']))
        {
            return array();
        }

        return $config['rv-base']['permissions']['acl']['parent_roles_providers'];
    }
}
