<?php

namespace RvBase\Mvc;

use RvBase\Permissions\Acl\AclAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc as ZendMvc;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\AclInterface;
use ArrayObject;
use Zend\Stdlib\ArrayUtils;

/**
 * Class DispatchListener
 * @package RvBase\Mvc
 */
class DispatchListener implements ListenerAggregateInterface, AclAwareInterface
{
    protected $acl;

    protected $role = false;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 10);
    }

    /**
     * Detach listeners from an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onDispatch(MvcEvent $e)
    {
        $routeMatch       = $e->getRouteMatch();
        $controllerName   = $routeMatch->getParam('controller', 'not-found');
        $application      = $e->getApplication();

        $resource   = $routeMatch->getParam('resource');
        $privilege   = $routeMatch->getParam('privilege');
        if(empty($resource) || empty($privilege))
        {
            //Nothing to check
            return $e->getResult();
        }

        $acl = $this->getAcl();
        if($acl === null)
        {
            $return = $this->marshalNotAllowedEvent(Application::ERROR_ACL_NOT_FOUND, $controllerName, $e, $application);
            return $this->complete($return, $e);
        }

        $role = $this->getRole();
        if($role === false)
        {
            $return = $this->marshalNotAllowedEvent(Application::ERROR_ACL_ROLE_NOT_SET, $controllerName, $e, $application);
            return $this->complete($return, $e);
        }

        try
        {
            if($acl->isAllowed($role, $resource, $privilege))
            {
                return $e->getResult();
            }
        }
        catch(\Exception $exception)
        {
            $return = $this->marshalNotAllowedEvent(Application::ERROR_ACL_FAILED, $controllerName, $e, $application, $exception);
            return $this->complete($return, $e);
        }
        $return = $this->marshalNotAllowedEvent(Application::ERROR_CONTROLLER_NOT_ALLOWED, $controllerName, $e, $application);
        return $this->complete($return, $e);
    }

    /**
     * Marshal a controller not allowed exception event
     *
     * @param  string $type
     * @param  string $controllerName
     * @param  MvcEvent $event
     * @param  ZendMvc\Application $application
     * @param  \Exception $exception
     * @return mixed
     */
    protected function marshalNotAllowedEvent(
        $type,
        $controllerName,
        MvcEvent $event,
        ZendMvc\Application $application,
        \Exception $exception = null
    )
    {
        $event->setError($type)
            ->setController($controllerName)
        ;
        if ($exception !== null) {
            $event->setParam('exception', $exception);
        }

        $events  = $application->getEventManager();
        $results = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        $return  = $results->last();
        if (! $return) {
            $return = $event->getResult();
        }
        return $return;
    }

    /**
     * Complete the dispatch
     *
     * @param  mixed $return
     * @param  MvcEvent $event
     * @return mixed
     */
    protected function complete($return, MvcEvent $event)
    {
        if (!is_object($return)) {
            if (ArrayUtils::hasStringKeys($return)) {
                $return = new ArrayObject($return, ArrayObject::ARRAY_AS_PROPS);
            }
        }
        $event->setResult($return);
        return $return;
    }

    /**
     * @return AclInterface
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param AclInterface $acl
     */
    public function setAcl(AclInterface $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}
