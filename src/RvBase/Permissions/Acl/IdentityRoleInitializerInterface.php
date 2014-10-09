<?php

namespace RvBase\Permissions\Acl;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Interface IdentityRoleInitializerInterface
 * @package RvBase\Permissions\Acl
 */
interface IdentityRoleInitializerInterface
{
    /**
     * @param Acl $acl
     * @param mixed $identity
     * @return RoleInterface
     */
    public function initialize(Acl $acl, $identity);
}
