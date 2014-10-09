<?php

namespace RvBase\ServiceFactory\Acl;

use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourcesConfigInitializer
 * @package RvBase\ServiceFactory\Acl
 */
class ResourcesConfigInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if(!$instance instanceof Acl)
        {
            return;
        }

        $config = $this->getConfig($serviceLocator);

        array_walk(
            $config,
            function($parents, $resource, Acl $acl)
            {
                $acl->addResource($resource, $parents);
            },
            $instance
        );
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config'))
        {
            return array();
        }

        $config = $serviceLocator->get('Config');
        if(!isset($config['rv-base']['permissions']['acl']['resources']) || !is_array($config['rv-base']['permissions']['acl']['resources']))
        {
            return array();
        }

        return $config['rv-base']['permissions']['acl']['resources'];
    }
}
