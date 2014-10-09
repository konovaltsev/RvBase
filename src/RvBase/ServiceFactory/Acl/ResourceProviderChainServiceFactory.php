<?php

namespace RvBase\ServiceFactory\Acl;

use RvBase\Permissions\Acl\Resource\DirectResourceProvider;
use RvBase\Permissions\Acl\Resource\ObjectResourceProviderChain;
use RvBase\Permissions\Acl\Resource\ResourceProviderChain;
use RvBase\Permissions\Acl\Resource\ResourceProviderInterface;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceProviderChainServiceFactory
 * @package RvBase\ServiceFactory\Acl
 */
class ResourceProviderChainServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $resourceProvider = $this->createResourceProvider($serviceLocator);

        $this->preInit($resourceProvider, $serviceLocator);
        $this->init($resourceProvider, $serviceLocator);
        $this->postInit($resourceProvider, $serviceLocator);

        return $resourceProvider;
    }

    protected function createResourceProvider(ServiceLocatorInterface $serviceLocator)
    {
        return new ResourceProviderChain();
    }

    protected function init(ResourceProviderChain $resourceProvider, ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig('resource_providers', $serviceLocator);

        array_walk(
            $config,
            function($options) use($resourceProvider, $serviceLocator)
            {
                switch(true)
                {
                    case isset($options['class']):
                        $class = $options['class'];
                        $provider = new $class();
                        break;
                    case isset($options['service']):
                        $provider = $serviceLocator->get($options['service']);
                        break;
                    default:
                        $provider = null;
                }

                if(!$provider instanceof ResourceProviderInterface)
                {
                    throw new Exception\RuntimeException('Provider must implements ResourceProviderInterface');
                }

                $resourceProvider->addProvider($provider);
            }
        );
    }

    protected function preInit(ResourceProviderChain $resourceProvider, ServiceLocatorInterface $serviceLocator)
    {
        $objectResourceProvider = new ObjectResourceProviderChain();

        $this->initObjectProviderChain($objectResourceProvider, $serviceLocator);

        $resourceProvider->addProvider($objectResourceProvider);
    }

    protected function postInit(ResourceProviderChain $resourceProvider, ServiceLocatorInterface $serviceLocator)
    {
        $resourceProvider->addProvider(new DirectResourceProvider());
    }

    protected function initObjectProviderChain(ObjectResourceProviderChain $resourceProvider, ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig('object_resource_providers', $serviceLocator);

        array_walk(
            $config,
            function($options, $objectClass) use($resourceProvider, $serviceLocator)
            {
                switch(true)
                {
                    case isset($options['class']):
                        $class = $options['class'];
                        $provider = new $class();
                        break;
                    case isset($options['service']):
                        $provider = $serviceLocator->get($options['service']);
                        break;
                    default:
                        $provider = null;
                }

                if(!$provider instanceof ResourceProviderInterface)
                {
                    throw new Exception\RuntimeException('Provider must implements ResourceProviderInterface');
                }

                $resourceProvider->addProvider($objectClass, $provider);
            }
        );
    }

    protected function getConfig($key, ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config'))
        {
            return array();
        }

        $config = $serviceLocator->get('Config');
        if(!isset($config['rv-base']['permissions']['acl'][$key]) || !is_array($config['rv-base']['permissions']['acl'][$key]))
        {
            return array();
        }

        return $config['rv-base']['permissions']['acl'][$key];
    }
}
