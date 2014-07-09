<?php

namespace RvBase\Permissions\Acl;

use Zend\Permissions\Acl as ZendAcl;

/**
 * Class InjectRoleService
 * @package RvBase\Permissions\Acl
 */
class InjectRoleService implements InjectRoleServiceInterface, ZendAcl\AclInterface
{
    protected $fakeRole = 'role.fake';

    /**
     * @var InjectRoleStrategyInterface[]
     */
    protected $strategies = array();

    private $currentRole;
    private $currentResource;
    private $currentRoleSet;

    public function addStrategy(InjectRoleStrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    public function inject(ZendAcl\Acl $acl, $role = null, $resource = null)
    {
        $this->free($acl);

        if($role !== null && $resource !== null)
        {
            $roles = $this->getResourceDependentRoles($role, $resource);
            if(count($roles) > 1)
            {
                $acl->addRole($this->fakeRole, $roles);
                $role = $this->fakeRole;
            }
        }

        return $role;
    }

    public function free(ZendAcl\Acl $acl)
    {
        if($acl->hasRole($this->fakeRole))
        {
            $acl->removeRole($this->fakeRole);
        }
    }

    protected function getResourceDependentRoles($role, $resource)
    {
        /**
         * @todo: deep to `allResources` fake resource
         */
        $roleSet = $this->getRoles($role, $resource);
        $result = $roleSet->getFirsts();
        $result[] = $role;
        $result = array_merge($result, $roleSet->getLasts());
        return $result;
    }


    /**
     * @param $role
     * @param $resource
     * @return Role\InjectRoleSet
     */
    public function getRoles($role, $resource)
    {
        $this->currentRoleSet = new Role\InjectRoleSet();
        $this->currentRole = $role;
        $this->currentResource = $resource;

        array_walk(
            $this->strategies,
            array($this, 'injectFromStrategy')
        );

        return $this->currentRoleSet;
    }

    private function injectFromStrategy(InjectRoleStrategyInterface $strategy)
    {
        $strategy->inject($this->currentRoleSet, $this->currentRole, $this->currentResource);
    }
}
