<?php

namespace RvBase\Permissions\Acl\Role;

/**
 * Interface RoleProviderInterface
 * @package RvBase\Permissions\Acl\Role
 */
interface RoleProviderInterface
{
    /**
     * Get initialized role for identity
     *
     * @param $identity
     * @return mixed
     */
    public function getRole($identity);
}
