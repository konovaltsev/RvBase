<?php

namespace RvBase\Permissions\Acl\Resource;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Interface ResourceProviderInterface
 * @package RvBase\Permissions\Acl\Resource
 */
interface ResourceProviderInterface
{
    /**
     * @param mixed $resource
     * @return string|ResourceInterface|false
     */
    public function getResource($resource);

    /**
     * @param mixed $resource
     * @return string|ResourceInterface|null|false
     */
    public function getParentResource($resource);
}
