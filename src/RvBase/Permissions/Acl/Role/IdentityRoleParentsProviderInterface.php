<?php

namespace RvBase\Permissions\Acl\Role;

/**
 * Interface IdentityRoleParentsProviderInterface
 * @package RvBase\Permissions\Acl\Role
 */
interface IdentityRoleParentsProviderInterface
{
    /**
     * Get parent roles for identity role
     *
     * @param $identity
     * @return mixed
     */
    public function getParentRoles($identity);
}