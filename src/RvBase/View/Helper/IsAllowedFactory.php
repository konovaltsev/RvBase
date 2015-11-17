<?php

namespace RvBase\View\Helper;

use RvBase\ServiceProvider\PermissionsServiceProviderTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Класс IsAllowedFactory
 *
 * @package RvBase\View\Helper
 */
class IsAllowedFactory implements FactoryInterface
{
    use PermissionsServiceProviderTrait;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new IsAllowed(
            $this->getPermissionsService($serviceLocator)
        );
    }
}
