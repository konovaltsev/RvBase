<?php

namespace RvBase\Mail;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MessageProviderServiceFactory
 * @package RvBase\Mail
 */
class MessageProviderServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MessageProvider($this->getOptions($serviceLocator));
    }

    protected function getOptions(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        $options = array();
        if(isset($config['options']) && is_array($config['options']))
        {
            $options = $config['options'];
        }
        return $options;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config')) {
            return array();
        }
        $config = $serviceLocator->get('Config');

        if (!isset($config['rv-base'])
            || !is_array($config['rv-base'])
        ) {
            return array();
        }
        $config = $config['rv-base'];

        if (!isset($config['mail'])
            || !is_array($config['mail'])
        ) {
            return array();
        }
        $config = $config['mail'];

        if (!isset($config['messages'])
            || !is_array($config['messages'])
        ) {
            return array();
        }

        return $config['messages'];
    }
}
