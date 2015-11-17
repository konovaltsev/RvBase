<?php

namespace RvBase\Permissions;

/**
 * Interface PermissionsAwareInterface
 * @package RvBase\Permissions
 */
interface PermissionsAwareInterface
{
    /**
     * Set permissions service
     *
     * @param PermissionsInterface $permissions
     * @return mixed
     */
    public function setPermissions(PermissionsInterface $permissions);
}
