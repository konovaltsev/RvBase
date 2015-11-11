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
}
