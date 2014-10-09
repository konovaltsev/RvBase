<?php

namespace RvBase\Permissions\Acl\Resource;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class ObjectResourceProviderChain
 * @package RvBase\Permissions\Acl\Resource
 */
class ObjectResourceProviderChain implements ResourceProviderInterface
{
    /**
     * @var ResourceProviderInterface[]
     */
    protected $objectResourceProviders = array();

    /**
     * @param mixed $resource
     * @return string|ResourceInterface|false
     */
    public function getResource($resource)
    {
        $objectClass = get_class($resource);
        if(array_key_exists($objectClass, $this->objectResourceProviders))
        {
            return $this->objectResourceProviders[$objectClass]->getResource($resource);
        }

        return false;
    }

    /**
     * @param mixed $resource
     * @return string|ResourceInterface|null|false
     */
    public function getParentResource($resource)
    {
        $objectClass = get_class($resource);
        if(array_key_exists($objectClass, $this->objectResourceProviders))
        {
            return $this->objectResourceProviders[$objectClass]->getParentResource($resource);
        }

        return false;
    }

    public function addProvider($class, ResourceProviderInterface $provider)
    {
        $this->objectResourceProviders[$class] = $provider;
    }
}
