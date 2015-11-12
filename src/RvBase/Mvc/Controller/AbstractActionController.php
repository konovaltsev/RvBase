<?php

namespace RvBase\Mvc\Controller;

use RvBase\Permissions\PermissionsInterface;
use RvBase\ServiceProvider\PermissionsServiceProviderTrait;
use Zend\Mvc\Controller\AbstractActionController as BaseController;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\ConsoleModel;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractActionController
 * @package RvBase\Mvc\Controller
 * @method ConsoleModel|ViewModel notAllowed
 */
abstract class AbstractActionController extends BaseController
{
    use PermissionsServiceProviderTrait;

    public function notAllowedAction()
    {
        return $this->notAllowed();
    }

    /**
     * Is permission allowed on something fo current identity
     *
     * @param string|ResourceInterface|object $resource
     * @param null $privilege
     * @return bool
     */
    protected function isAllowed($resource, $privilege = null)
    {
        return $this->getPermissions()->isAllowed($resource, $privilege);
    }

    /**
     * @return PermissionsInterface
     */
    protected function getPermissions()
    {
        return $this->getPermissionsService($this->getServiceLocator());
    }
}
