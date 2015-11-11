<?php

namespace RvBase\Permissions\Acl\Resource;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class ResourceProviderChain
 * @package RvBase\Permissions\Acl\Resource
 */
class ResourceProviderChain implements ResourceProviderInterface
{
    /**
     * @var ResourceProviderInterface[]
     */
    protected $resourceProviders = array();

    /**
     * @param mixed $resource
     * @return string|ResourceInterface|false
     */
    public function getResource($resource)
    {
        foreach($this->resourceProviders as $provider)
        {
            $aclResource = $provider->getResource($resource);
            if($aclResource === false)
            {
                continue;
            }

            return $aclResource;
        }

        return false;
    }

    /**
     * @param mixed $resource
     * @return string|ResourceInterface|null|false
     */
    public function getParentResource($resource)
    {
        foreach($this->resourceProviders as $provider)
        {
            $aclResource = $provider->getParentResource($resource);
            if($aclResource === false)
            {
                continue;
            }

            return $aclResource;
        }

        return false;
    }

    public function addProvider(ResourceProviderInterface $provider)
    {
        $this->resourceProviders[] = $provider;
    }
}
