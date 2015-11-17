<?php

namespace RvBase\Permissions;

/**
 * Class PermissionsAwareTrait
 * @package RvBase\Permissions
 */
trait PermissionsAwareTrait
{
    /** @var PermissionsInterface */
    protected $permissions;

    /**
     * Set permissions service
     *
     * @param PermissionsInterface $permissions
     * @return $this
     */
    public function setPermissions(PermissionsInterface $permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }
}
