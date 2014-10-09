<?php

namespace RvBase\Permissions\Acl\Role;

use Zend\Stdlib\SplPriorityQueue;

/**
 * Class IdentityIdentityRoleParentsProviderChain
 * @package RvBase\Permissions\Acl\Role
 */
class IdentityRoleParentsProviderChain implements IdentityRoleParentsProviderInterface
{
    /** @var  SplPriorityQueue */
    protected $parentsProviders;

    public function __construct()
    {
        $this->parentsProviders = new SplPriorityQueue();
    }

    /**
     * Get parent roles for identity role
     *
     * @param $identity
     * @return mixed
     */
    public function getParentRoles($identity)
    {
        $allParentRoles = array();

        /** @var IdentityRoleParentsProviderInterface $parentsProvider */
        foreach($this->parentsProviders as $parentsProvider)
        {
            $parentRoles = $parentsProvider->getParentRoles($identity);
            if(!empty($parentRoles))
            {
                if(!is_array($parentRoles))
                {
                    $parentRoles = array($parentRoles);
                }
                $allParentRoles = array_merge($allParentRoles, $parentRoles);
            }
        }

        return $allParentRoles;
    }

    public function addParentsProvider(IdentityRoleParentsProviderInterface $provider, $priority = 1)
    {
        $this->parentsProviders->insert($provider, $priority);
    }
}