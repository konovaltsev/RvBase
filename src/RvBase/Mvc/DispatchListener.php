<?php

namespace RvBase\Mvc;

use RvBase\Permissions\PermissionsInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc as ZendMvc;
use Zend\Mvc\MvcEvent;
use ArrayObject;
use Zend\Stdlib\ArrayUtils;

/**
 * Class DispatchListener
 * @package RvBase\Mvc
 */
class DispatchListener implements ListenerAggregateInterface
{
    /** @var PermissionsInterface */
    private $permissions;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    public function __construct(
        PermissionsInterface $permissions
    )
    {
        $this->permissions = $permissions;
    }

    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'validateByRouteParams'), 10);
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

    public function validateByRouteParams(MvcEvent $e)
    {
        $routeMatch       = $e->getRouteMatch();
        $controllerName   = $routeMatch->getParam('controller', 'not-found');
        $application      = $e->getApplication();

        $resource   = $routeMatch->getParam('acl_resource');
        $privilege  = $routeMatch->getParam('acl_privilege');
        if(empty($resource))
        {
            //Nothing to check
            return $e->getResult();
        }

        $permissions = $this->getPermissions();

        try
        {
            if($permissions->isAllowed($resource, $privilege))
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
     * @param  ZendMvc\ApplicationInterface $application
     * @param  \Exception $exception
     * @return mixed
     */
    protected function marshalNotAllowedEvent(
        $type,
        $controllerName,
        MvcEvent $event,
        ZendMvc\ApplicationInterface $application,
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
     * @return PermissionsInterface
     */
    private function getPermissions()
    {
        return $this->permissions;
    }
}
