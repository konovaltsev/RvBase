<?php

namespace RvBase\Mvc\View\Http;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc as ZendMvc;
use RvBase\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

/**
 * Class RouteNotAllowedStrategy
 * @package RvBase\Mvc\View\Http
 */
class RouteNotAllowedStrategy extends AbstractListenerAggregate
{
    /**
     * Whether or not to display exceptions related to the 403 condition
     *
     * @var bool
     */
    protected $displayExceptions = false;

    /**
     * Whether or not to display the reason for a 403
     *
     * @var bool
     */
    protected $displayNotAllowedReason = false;

    /**
     * Template to use to report page not allowed conditions
     *
     * @var string
     */
    protected $notAllowedTemplate = 'error';

    /**
     * The reason for a not-allowed condition
     *
     * @var false|string
     */
    protected $reason = false;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'prepareNotAllowedViewModel'), -90);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'detectNotAllowedError'));
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'prepareNotAllowedViewModel'));
    }

    /**
     * Set value indicating whether or not to display exceptions related to a not-allowed condition
     *
     * @param  bool $displayExceptions
     * @return RouteNotAllowedStrategy
     */
    public function setDisplayExceptions($displayExceptions)
    {
        $this->displayExceptions = (bool) $displayExceptions;
        return $this;
    }

    /**
     * Should we display exceptions related to a not-allowed condition?
     *
     * @return bool
     */
    public function displayExceptions()
    {
        return $this->displayExceptions;
    }

    /**
     * Set value indicating whether or not to display the reason for a not-allowed condition
     *
     * @param  bool $displayNotAllowedReason
     * @return RouteNotAllowedStrategy
     */
    public function setDisplayNotAllowedReason($displayNotAllowedReason)
    {
        $this->displayNotAllowedReason = (bool) $displayNotAllowedReason;
        return $this;
    }

    /**
     * Should we display the reason for a not-allowed condition?
     *
     * @return bool
     */
    public function displayNotAllowedReason()
    {
        return $this->displayNotAllowedReason;
    }

    /**
     * Get template for not allowed conditions
     *
     * @param  string $notAllowedTemplate
     * @return RouteNotAllowedStrategy
     */
    public function setNotAllowedTemplate($notAllowedTemplate)
    {
        $this->notAllowedTemplate = (string) $notAllowedTemplate;
        return $this;
    }

    /**
     * Get template for not allowed conditions
     *
     * @return string
     */
    public function getNotAllowedTemplate()
    {
        return $this->notAllowedTemplate;
    }

    /**
     * Detect if an error is a 403 condition
     *
     * If a "controller not allowed" sets the response status code to 403.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function detectNotAllowedError(MvcEvent $e)
    {
        $error = $e->getError();
        if (empty($error)) {
            return;
        }

        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_ALLOWED:
            case Application::ERROR_ACL_FAILED:
            case Application::ERROR_ACL_NOT_FOUND:
            case Application::ERROR_ACL_ROLE_NOT_SET:
                $this->reason = $error;
                $response = $e->getResponse();
                if (!$response) {
                    $response = new HttpResponse();
                    $e->setResponse($response);
                }
                $response->setStatusCode(403);
                break;
            default:
                return;
        }
    }

    /**
     * Create and return a 403 view model
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function prepareNotAllowedViewModel(MvcEvent $e)
    {
        $vars = $e->getResult();
        if ($vars instanceof Response) {
            // Already have a response as the result
            return;
        }

        $response = $e->getResponse();
        if ($response->getStatusCode() != 403) {
            // Only handle 403 responses
            return;
        }

        if (!$vars instanceof ViewModel) {
            $model = new ViewModel();
            if (is_string($vars)) {
                $model->setVariable('message', $vars);
            } else {
                $model->setVariable('message', 'Page not allowed.');
            }
        } else {
            $model = $vars;
            if ($model->getVariable('message') === null) {
                $model->setVariable('message', 'Page not allowed.');
            }
        }

        $model->setTemplate($this->getNotAllowedTemplate());

        // If displaying reasons, inject the reason
        $this->injectNotAllowedReason($model);

        // If displaying exceptions, inject
        $this->injectException($model, $e);

        // Inject controller if we're displaying either the reason or the exception
        $this->injectController($model, $e);

        $e->setResult($model);
    }

    /**
     * Inject the not-allowed reason into the model
     *
     * If $displayNotAllowedReason is enabled, checks to see if $reason is set,
     * and, if so, injects it into the model. If not, it injects
     * Application::ERROR_CONTROLLER_CANNOT_DISPATCH.
     *
     * @param  ViewModel $model
     * @return void
     */
    protected function injectNotAllowedReason(ViewModel $model)
    {
        if (!$this->displayNotAllowedReason()) {
            return;
        }

        // no route match, controller not allowed, or controller invalid
        if ($this->reason) {
            $model->setVariable('reason', $this->reason);
            return;
        }

        // otherwise, must be a case of the controller not being able to
        // dispatch itself.
        $model->setVariable('reason', ZendMvc\Application::ERROR_CONTROLLER_CANNOT_DISPATCH);
    }

    /**
     * Inject the exception message into the model
     *
     * If $displayExceptions is enabled, and an exception is allowed in the
     * event, inject it into the model.
     *
     * @param  ViewModel $model
     * @param  MvcEvent $e
     * @return void
     */
    protected function injectException($model, $e)
    {
        if (!$this->displayExceptions()) {
            return;
        }

        $model->setVariable('display_exceptions', true);

        $exception = $e->getParam('exception', false);
        if (!$exception instanceof \Exception) {
            return;
        }

        $model->setVariable('exception', $exception);
    }

    /**
     * Inject the controller and controller class into the model
     *
     * If either $displayExceptions or $displayNotAllowedReason are enabled,
     * injects the controllerClass from the MvcEvent. It checks to see if a
     * controller is present in the MvcEvent, and, if not, grabs it from
     * the route match if present; if a controller is allowed, it injects it into
     * the model.
     *
     * @param  ViewModel $model
     * @param  MvcEvent $e
     * @return void
     */
    protected function injectController($model, $e)
    {
        if (!$this->displayExceptions() && !$this->displayNotAllowedReason()) {
            return;
        }

        $controller = $e->getController();
        if (empty($controller)) {
            $routeMatch = $e->getRouteMatch();
            if (empty($routeMatch)) {
                return;
            }

            $controller = $routeMatch->getParam('controller', false);
            if (!$controller) {
                return;
            }
        }

        $controllerClass = $e->getControllerClass();
        $model->setVariable('controller', $controller);
        $model->setVariable('controller_class', $controllerClass);
    }
}
