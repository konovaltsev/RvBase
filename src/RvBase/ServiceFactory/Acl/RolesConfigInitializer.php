<?php

namespace RvBase\ServiceFactory\Acl;

use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RolesConfigInitializer
 * @package RvBase\Permissions\Acl
 */
class RolesConfigInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param                         $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if (!$instance instanceof Acl) {
            return;
        }

        $config = $this->getConfig($serviceLocator);

        array_walk(
            $config,
            function ($parents, $role, Acl $acl) {
                $acl->addRole($role, $parents);
            },
            $instance
        );
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config')) {
            return [];
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config['rv-base']['permissions']['acl']['roles']) || !is_array($config['rv-base']['permissions']['acl']['roles'])) {
            throw new Exception\RuntimeException('`roles` config does not exists in [\'rv-base\'][\'permissions\'][\'acl\']');
        }

        return $config['rv-base']['permissions']['acl']['roles'];
    }
}
