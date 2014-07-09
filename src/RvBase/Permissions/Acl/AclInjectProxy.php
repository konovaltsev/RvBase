<?php

namespace RvBase\Permissions\Acl;

use Zend\Permissions\Acl as ZendAcl;
use Zend\Permissions\Acl\Resource;
use Zend\Permissions\Acl\Role;

/**
 * Class AclInjectProxy
 * @package RvBase\Permissions\Acl
 */
class AclInjectProxy implements ZendAcl\AclInterface, InjectRoleServiceAwareInterface
{
    protected $acl;
    protected $injectRoleService = null;

    public function __construct(ZendAcl\Acl $acl)
    {
        $this->acl = $acl;
    }

    /**
     * Returns true if and only if the Resource exists in the ACL
     *
     * The $resource parameter can either be a Resource or a Resource identifier.
     *
     * @param  Resource\ResourceInterface|string $resource
     * @return bool
     */
    public function hasResource($resource)
    {
        return $this->acl->hasResource($resource);
    }

    /**
     * Returns true if and only if the Role has access to the Resource
     *
     * The $role and $resource parameters may be references to, or the string identifiers for,
     * an existing Resource and Role combination.
     *
     * If either $role or $resource is null, then the query applies to all Roles or all Resources,
     * respectively. Both may be null to query whether the ACL has a "blacklist" rule
     * (allow everything to all). By default, Zend\Permissions\Acl creates a "whitelist" rule (deny
     * everything to all), and this method would return false unless this default has
     * been overridden (i.e., by executing $acl->allow()).
     *
     * If a $privilege is not provided, then this method returns false if and only if the
     * Role is denied access to at least one privilege upon the Resource. In other words, this
     * method returns true if and only if the Role is allowed all privileges on the Resource.
     *
     * This method checks Role inheritance using a depth-first traversal of the Role registry.
     * The highest priority parent (i.e., the parent most recently added) is checked first,
     * and its respective parents are checked similarly before the lower-priority parents of
     * the Role are checked.
     *
     * @param  Role\RoleInterface|string $role
     * @param  Resource\ResourceInterface|string $resource
     * @param  string $privilege
     * @return bool
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        $acl = $this->acl;

        if(null === ($injector = $this->getInjectRoleService()))
        {
            return $acl->isAllowed($role, $resource, $privilege);
        }

        $role = $injector->inject($acl, $role, $resource);

        $return = $acl->isAllowed($role, $resource, $privilege);

        $injector->free($acl);

        return $return;
    }

    public function setInjectRoleService(InjectRoleServiceInterface $service)
    {
        $this->injectRoleService = $service;
        return $this;
    }

    /**
     * @return null|InjectRoleServiceInterface
     */
    public function getInjectRoleService()
    {
        return $this->injectRoleService;
    }
}
