<?php

namespace RvBase\Permissions\Acl;

/**
 * Class InjectRoleStrategyInterface
 * @package RvBase\Permissions\Acl
 */
interface InjectRoleStrategyInterface
{
    public function inject(Role\InjectRoleSet $roleSet, $role, $resource);
}
