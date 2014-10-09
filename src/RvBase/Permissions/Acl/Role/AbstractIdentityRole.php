<?php

namespace RvBase\Permissions\Acl\Role;

use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Class AbstractIdentityRole
 * @package RvBase\Permissions\Acl\Role
 */
abstract class AbstractIdentityRole implements RoleInterface
{
    protected $identity;

    protected $guestRoleId = 'guest';

    protected $roleId;

    public function __construct($identity)
    {
        $this->identity = $identity;
    }

    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        if($this->identity === null)
        {
            return $this->guestRoleId;
        }

        if($this->roleId === null)
        {
            $this->roleId = $this->createRoleId();
        }

        return $this->roleId;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return string role Id
     */
    abstract protected function createRoleId();

    /**
     * Defined by RoleInterface; returns the Role identifier
     * Proxies to getRoleId()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRoleId();
    }
}