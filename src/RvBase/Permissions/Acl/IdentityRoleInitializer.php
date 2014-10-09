<?php

namespace RvBase\Permissions\Acl;

use RvBase\Permissions\Acl\Role\IdentityRoleProviderInterface;
use RvBase\Permissions\Acl\Role\IdentityRoleParentsProviderInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Class IdentityRoleInitializer
 * @package RvBase\Permissions\Acl
 */
class IdentityRoleInitializer implements IdentityRoleInitializerInterface
{
    /** @var IdentityRoleProviderInterface */
    protected $roleProvider;

    /** @var IdentityRoleParentsProviderInterface */
    protected $parentRolesProvider;

    public function __construct(
        IdentityRoleProviderInterface $roleProvider,
        IdentityRoleParentsProviderInterface $parentRolesProvider
    )
    {
        $this->roleProvider = $roleProvider;
        $this->parentRolesProvider = $parentRolesProvider;
    }

    /**
     * @param Acl $acl
     * @param mixed $identity
     * @return RoleInterface|string
     */
    public function initialize(Acl $acl, $identity)
    {
        $role = $this->roleProvider->getRole($identity);
        if(!$acl->hasRole($role))
        {
            $acl->addRole($role, $this->parentRolesProvider->getParentRoles($identity));
        }

        return $role;
    }
}
