<?php

namespace RvBase\Permissions;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Класс AclPermissionsInterface
 *
 * @package RvBase\Permissions
 */
interface AclPermissionsInterface extends PermissionsInterface
{
    /**
     * Is resource exists for some object or string or resource fo current identity
     *
     * @param mixed $resource any resource
     * @return bool
     */
    public function hasResource($resource);

    /**
     * Get acl resource by some object or string or resource
     *
     * @param mixed $resource any resource
     * @return string|ResourceInterface
     */
    public function getResource($resource);
}
