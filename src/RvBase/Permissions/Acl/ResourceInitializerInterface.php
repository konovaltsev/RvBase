<?php

namespace RvBase\Permissions\Acl;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Interface ResourceInitializerInterface
 * @package RvBase\Permissions\Acl
 */
interface ResourceInitializerInterface
{
    /**
     * @param Acl $acl
     * @param mixed $resource
     * @return ResourceInterface
     */
    public function initialize(Acl $acl, $resource);
}
