<?php

namespace RvBase\Permissions\Acl\Role;

use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Class InjectRoleSet
 * @package RvBase\Permissions\Acl\Role
 */
class InjectRoleSet
{
    const METHOD_FIRST = 'first';
    const METHOD_LAST = 'last';

    protected $roles = array(
        self::METHOD_FIRST => array(),
        self::METHOD_LAST => array(),
    );

    /**
     * @param $role
     * @param string $method По умолчанию с наименьшим приоритетом
     */
    public function add($role, $method = self::METHOD_FIRST)
    {
        $key = ($role instanceof RoleInterface)? $role->getRoleId() : $role;
        $method = $method == self::METHOD_LAST? self::METHOD_LAST : self::METHOD_FIRST;
        $this->roles[$method][$key] = $method;
    }

    public function addFirst($role)
    {
        $this->add($role, self::METHOD_FIRST);
    }

    public function addLast($role)
    {
        $this->add($role, self::METHOD_LAST);
    }

    public function getFirsts()
    {
        return array_values($this->roles[self::METHOD_FIRST]);
    }

    public function getLasts()
    {
        return array_values($this->roles[self::METHOD_LAST]);
    }
}
