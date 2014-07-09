<?php

namespace RvBase\Permissions\Acl;

use Zend\Permissions\Acl as ZendAcl;

/**
 * Class DepServiceInterface
 * @package RvBase\Permissions\Acl
 */
interface InjectRoleServiceInterface
{

    public function inject(ZendAcl\Acl $acl, $role = null, $resource = null);

    public function free(ZendAcl\Acl $acl);
}
