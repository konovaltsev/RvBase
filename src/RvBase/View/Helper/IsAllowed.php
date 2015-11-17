<?php

namespace RvBase\View\Helper;

use RvBase\Permissions\PermissionsInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Класс IsAllowed
 *
 * @package RvBase\View\Helper
 */
class IsAllowed extends AbstractHelper
{
    /** @var PermissionsInterface  */
    private $permissions;

    public function __construct(PermissionsInterface $permissions)
    {
        $this->permissions = $permissions;
    }

    public function __invoke($entity, $privilege = null)
    {
        return $this->permissions->isAllowed($entity, $privilege);
    }
}
