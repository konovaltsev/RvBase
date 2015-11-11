<?php

namespace RvBase\Permissions;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Класс PermissionsInterface
 *
 * @package RvBase\Permissions
 */
interface PermissionsInterface
{
    /**
     * Is permission allowed on some object or string or resource fo current identity
     *
     * @param mixed $resource any resource
     * @param string $privilege
     * @return bool
     */
    public function isAllowed($resource, $privilege = null);

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
