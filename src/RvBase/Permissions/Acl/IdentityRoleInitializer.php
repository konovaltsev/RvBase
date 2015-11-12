<?php

namespace RvBase\Permissions\Acl;

use RvBase\Permissions\Acl\Role\IdentityRoleProviderInterface;
use RvBase\Permissions\Acl\Role\IdentityRoleParentsProviderInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Stdlib\SplPriorityQueue;

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
        $this->roleProvider        = $roleProvider;
        $this->parentRolesProvider = $parentRolesProvider;
    }

    /**
     * @param Acl   $acl
     * @param mixed $identity
     * @return RoleInterface|string
     */
    public function initialize(Acl $acl, $identity)
    {
        $role = $this->roleProvider->getRole($identity);
        if (!$acl->hasRole($role)) {
            $parents = $this->parentRolesProvider->getParentRoles($identity);
            if (!is_array($parents)) {
                $parents = [$parents];
            }

            $queue = new SplPriorityQueue();
            foreach ($parents as $key => $value) {
                if (is_int($key)) {
                    $queue->insert($value, 1);
                } else {
                    $queue->insert($key, $value);
                }
            }

            $acl->addRole($role, $queue->toArray());
        }

        return $role;
    }
}
