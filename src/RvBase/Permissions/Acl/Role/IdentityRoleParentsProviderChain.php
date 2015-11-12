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
        $allParentRoles = [];

        foreach ($this->parentsProviders as $parentsProviderData) {
            /** @var IdentityRoleParentsProviderInterface $parentsProvider */
            $parentsProvider = $parentsProviderData['provider'];
            $parentsPriority = $parentsProviderData['priority'];
            $parentRoles     = $parentsProvider->getParentRoles($identity);

            if (!empty($parentRoles)) {
                if (!is_array($parentRoles)) {
                    $parentRoles = [$parentRoles];
                }

                $normalizedParentRoles = [];
                foreach ($parentRoles as $key => $value) {
                    if (is_numeric($key)) {
                        $normalizedParentRoles[$value] = $parentsPriority;
                    } else {
                        $normalizedParentRoles[$key] = $value;
                    }
                }

                $allParentRoles = array_merge($allParentRoles, $normalizedParentRoles);
            }
        }

        return $allParentRoles;
    }

    public function addParentsProvider(IdentityRoleParentsProviderInterface $provider, $priority = 1)
    {
        $this->parentsProviders->insert(
            [
                'provider' => $provider,
                'priority' => $priority,
            ],
            $priority
        );
    }
}