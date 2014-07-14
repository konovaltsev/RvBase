<?php

namespace RvBase\Authentication;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IdentityInitializer
 * @package RvBase\Authentication
 */
class IdentityInitializer implements InitializerInterface
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
        if($instance instanceof IdentityAwareInterface)
        {
            $identityService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
            if($identityService->hasIdentity())
            {
                $instance->setIdentity($identityService->getIdentity());
            }
        }
    }
}
