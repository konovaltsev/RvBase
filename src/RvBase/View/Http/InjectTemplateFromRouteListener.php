<?php

namespace RvBase\View\Http;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface as Events;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface as ViewModel;

/**
 * Class InjectTemplateFromRouteListener
 * @package RvBase\View\Http
 */
class InjectTemplateFromRouteListener extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(Events $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'injectTemplate'], -85);
    }

    /**
     * Inject a template into the view model, if none present
     *
     * Template is derived from the template found in the route match
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function injectTemplate(MvcEvent $e)
    {
        $model = $e->getResult();
        if (!$model instanceof ViewModel) {
            return;
        }
        $template = $model->getTemplate();
        if (!empty($template)) {
            return;
        }

        $routeMatch = $e->getRouteMatch();
        $template = $routeMatch->getParam('template', null);
        if (empty($template))
        {
            return;
        }

        $model->setTemplate($template);
    }
}
