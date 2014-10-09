<?php

namespace RvBase\Permissions\Acl\Role;

/**
 * Interface IdentityRoleProviderInterface
 * @package RvBase\Permissions\Acl\Role
 */
interface IdentityRoleProviderInterface
{
    public function getRole($identity);
}
