<?php

namespace RvBase\Mvc\View\Http;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use ArrayAccess;

/**
 * Class ViewManager
 * @package RvBase\Mvc\View\Http
 */
class ViewManager
{
    /**
     * @var object application configuration service
     */
    protected $config;

    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @var ServiceManager
     */
    protected $services;

    protected $routeNotAllowedStrategy;

    /**
     * Prepares the view layer
     *
     * @param  $event
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application  = $event->getApplication();
        $services     = $application->getServiceManager();
        $config       = $services->get('Config');
        $events       = $application->getEventManager();
        $sharedEvents = $events->getSharedManager();

        $this->config   = isset($config['view_manager']) && (is_array($config['view_manager']) || $config['view_manager'] instanceof ArrayAccess)
            ? $config['view_manager']
            : array();
        $this->services = $services;
        $this->event    = $event;

        $routeNotAllowedStrategy   = $this->getRouteNotAllowedStrategy();

        $events->attach($routeNotAllowedStrategy);

        $sharedEvents->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($routeNotAllowedStrategy, 'prepareNotAllowedViewModel'), -90);
    }

    /**
     * Instantiates and configures the "route not allowed", or 404, strategy
     *
     * @return RouteNotAllowedStrategy
     */
    public function getRouteNotAllowedStrategy()
    {
        if ($this->routeNotAllowedStrategy) {
            return $this->routeNotAllowedStrategy;
        }

        $this->routeNotAllowedStrategy = new RouteNotAllowedStrategy();

        $displayExceptions     = false;
        $displayNotAllowedReason = false;
        $notAllowedTemplate      = '403';

        if (isset($this->config['display_exceptions'])) {
            $displayExceptions = $this->config['display_exceptions'];
        }
        if (isset($this->config['display_not_allowed_reason'])) {
            $displayNotAllowedReason = $this->config['display_not_allowed_reason'];
        }
        if (isset($this->config['not_allowed_template'])) {
            $notAllowedTemplate = $this->config['not_allowed_template'];
        }

        $this->routeNotAllowedStrategy->setDisplayExceptions($displayExceptions);
        $this->routeNotAllowedStrategy->setDisplayNotAllowedReason($displayNotAllowedReason);
        $this->routeNotAllowedStrategy->setNotAllowedTemplate($notAllowedTemplate);

        $this->services->setService('RouteNotAllowedStrategy', $this->routeNotAllowedStrategy);
        $this->services->setAlias('RvBase\Mvc\View\RouteNotAllowedStrategy', 'RouteNotAllowedStrategy');
        $this->services->setAlias('RvBase\Mvc\View\Http\RouteNotAllowedStrategy', 'RouteNotAllowedStrategy');
        $this->services->setAlias('403Strategy', 'RouteNotAllowedStrategy');

        return $this->routeNotAllowedStrategy;
    }
}
