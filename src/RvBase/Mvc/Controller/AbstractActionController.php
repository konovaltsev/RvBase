<?php

namespace RvBase\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController as BaseController;
use Zend\View\Model\ConsoleModel;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractActionController
 * @package RvBase\Mvc\Controller
 * @method ConsoleModel|ViewModel notAllowed
 */
abstract class AbstractActionController extends BaseController
{
    public function notAllowedAction()
    {
        return $this->notAllowed();
    }
}
