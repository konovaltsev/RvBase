<?php

namespace RvBase\Permissions\Acl\Resource;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class DirectResourceProvider
 * @package RvBase\Permissions\Acl\Resource
 */
class DirectResourceProvider implements ResourceProviderInterface
{
    /**
     * @param mixed $resource
     * @return string|ResourceInterface|false
     */
    public function getResource($resource)
    {
        if($resource instanceof ResourceInterface || is_string($resource))
        {
            return $resource;
        }

        return false;
    }

    /**
     * @param mixed $resource
     * @return string|ResourceInterface|null|false
     */
    public function getParentResource($resource)
    {
        return false;
    }
}
