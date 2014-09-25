<?php

namespace RvBase\Mail;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AddressProviderServiceFactory
 * @package RvBase\Mail
 */
class AddressProviderServiceFactory implements FactoryInterface
{
    /** @var string Factory can be extended for overridden AddressProvider */
    protected $serviceClass = 'RvBase\Mail\AddressProvider';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = $this->createAddressProvider($serviceLocator);
        $this->initAddressProvider($service, $serviceLocator);
        return $service;
    }

    protected function createAddressProvider(ServiceLocatorInterface $serviceLocator)
    {
        return new $this->serviceClass($this->getConfig($serviceLocator));
    }

    /**
     * For extending
     *
     * @param AddressProviderInterface $service
     * @param ServiceLocatorInterface $serviceLocator
     */
    protected function initAddressProvider(AddressProviderInterface $service, ServiceLocatorInterface $serviceLocator)
    {

    }

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

        if (!isset($config['addresses'])
            || !is_array($config['addresses'])
        ) {
            return array();
        }

        return $config['addresses'];
    }
}
