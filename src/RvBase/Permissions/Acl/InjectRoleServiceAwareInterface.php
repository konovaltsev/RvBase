<?php

namespace RvBase\Permissions\Acl;


interface InjectRoleServiceAwareInterface
{
    public function setInjectRoleService(InjectRoleServiceInterface $service);

    /**
     * @return null|InjectRoleServiceInterface
     */
    public function getInjectRoleService();
}
