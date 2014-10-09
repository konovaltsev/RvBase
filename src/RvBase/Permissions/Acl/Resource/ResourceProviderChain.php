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

        throw new Exception\RuntimeException(
            sprintf(
                '%s: resource not found for `%s`',
                __METHOD__,
                is_object($resource)
                    ? get_class($resource)
                    :
                    (
                        is_scalar($resource)
                            ? sprintf('%s(%s)', gettype($resource), $resource)
                            : gettype($resource)
                    )
            )
        );
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

        throw new Exception\RuntimeException(
            sprintf(
                '%s: parent resource not found for `%s`',
                __METHOD__,
                is_object($resource)
                    ? get_class($resource)
                    :
                    (
                    is_scalar($resource)
                        ? sprintf('%s(%s)', gettype($resource), $resource)
                        : gettype($resource)
                    )
            )
        );
    }

    public function addProvider(ResourceProviderInterface $provider)
    {
        $this->resourceProviders[] = $provider;
    }
}
