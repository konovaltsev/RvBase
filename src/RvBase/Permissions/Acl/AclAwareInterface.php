<?php

namespace RvBase\Permissions\Acl;
use Zend\Permissions\Acl\AclInterface;

/**
 * Class AclAwareInterface
 * @package RvBase\Permissions\Acl
 */
interface AclAwareInterface
{
    public function getAcl();

    public function setAcl(AclInterface $acl);
}
