<?php

namespace RvBase\Mail\Transport;

use Zend\Mail\Transport\Factory;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TransportAbstractServiceFactory
 * @package RvBase\Mail
 */
class TransportAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);
        if (empty($config)) {
            return false;
        }

        return (isset($config[$requestedName]) && is_array($config[$requestedName]) && !empty($config[$requestedName]));
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config  = $this->getConfig($serviceLocator);
        $config  = $config[$requestedName];

        return Factory::create($config);
    }

    /**
     * Get transports configuration, if any
     *
     * @param  ServiceLocatorInterface $services
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $services)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$services->has('Config')) {
            $this->config = array();
            return $this->config;
        }

        $config = $services->get('Config');
        if (!isset($config['mail'])
            || !is_array($config['mail'])
        ) {
            $this->config = array();
            return $this->config;
        }

        $config = $config['mail'];
        if (!isset($config['transports'])
            || !is_array($config['transports'])
        ) {
            $this->config = array();
            return $this->config;
        }

        $this->config = $config['transports'];
        return $this->config;
    }
}
