<?php

namespace RvBase\Permissions;

/**
 * Класс PermissionsInterface
 *
 * @package RvBase\Permissions
 */
interface PermissionsInterface
{
    /**
     * Is permission allowed on some object fo current identity
     *
     * @param mixed $resource any resource
     * @param string $privilege
     * @return bool
     */
    public function isAllowed($resource, $privilege = null);
}
