<?php

namespace RvBase\ServiceFactory\Acl;

use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RulesConfigInitializer
 * @package RvBase\ServiceFactory\Acl
 */
class RulesConfigInitializer implements InitializerInterface
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
        $config = $this->getConfig($serviceLocator);

        array_walk(
            $config,
            function ($ruleConfig, $ruleKey, Acl $acl) use ($serviceLocator) {
                $assert = null;
                if (isset($ruleConfig['assert'])) {
                    switch (true) {
                        case isset($ruleConfig['assert']['class']):
                            $assertClass = $ruleConfig['assert']['class'];
                            $assert      = new $assertClass();
                            break;
                        case isset($ruleConfig['assert']['service']):
                            $assert = $serviceLocator->get($ruleConfig['assert']['service']);
                            break;
                        default:
                            throw new Exception\RuntimeException('Assert must have "class" or "service" key');
                    }
                }

                $acl->setRule(
                    Acl::OP_ADD,
                    $ruleConfig['type'],
                    $ruleConfig['roles'],
                    $ruleConfig['resources'],
                    $ruleConfig['privileges'],
                    $assert
                );
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
        if (!isset($config['rv-base']['permissions']['acl']['rules']) || !is_array($config['rv-base']['permissions']['acl']['rules'])) {
            throw new Exception\RuntimeException('`rules` config does not exists in [\'rv-base\'][\'permissions\'][\'acl\']');
        }

        return $config['rv-base']['permissions']['acl']['rules'];
    }
}
