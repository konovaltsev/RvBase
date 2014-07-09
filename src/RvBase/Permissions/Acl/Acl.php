<?php

namespace RvBase\Permissions\Acl;

use Zend\Permissions\Acl as ZendAcl;

/**
 * Class Acl
 * @package RvBase\Permissions\Acl
 */
class Acl extends ZendAcl\Acl implements InjectRoleServiceAwareInterface
{
    protected $injectRoleService = null;

    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        if(null === ($injector = $this->getInjectRoleService()))
        {
            return parent::isAllowed($role, $resource, $privilege);
        }

        $role = $injector->inject($this, $role, $resource);

        $return = parent::isAllowed($role, $resource, $privilege);

        $injector->free($this);

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
