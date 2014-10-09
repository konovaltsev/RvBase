<?php

namespace RvBase\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Exception;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Http\Response as HttpResponse;
use Zend\View\Model\ConsoleModel;
use Zend\View\Model\ViewModel;


/**
 * Класс NotAllowed
 *
 * @package RvBase\Mvc\Controller\Plugin
 */
class NotAllowed extends AbstractPlugin
{
	protected $event;
	protected $response;

	public function __invoke()
	{
		return $this->getModel();
	}

    /**
     * @return ConsoleModel|ViewModel
     */
	public function getModel()
	{
		$response   = $this->getResponse();
		$event      = $this->getEvent();
		$routeMatch = $event->getRouteMatch();
		$routeMatch->setParam('action', 'not-allowed');

		if ($response instanceof HttpResponse)
		{
			return $this->createHttpNotAllowedModel($response);
		}
		return $this->createConsoleNotAllowedModel($response);
	}

	/**
	 * Create an HTTP view model representing a "not allowed" page
	 *
	 * @param  HttpResponse $response
	 * @return ViewModel
	 */
	protected function createHttpNotAllowedModel(HttpResponse $response)
	{
		$response->setStatusCode(403);
		return new ViewModel(
			array(
				'content' => 'Page not allowed',
			)
		);
	}

	/**
	 * Create a console view model representing a "not allowed" action
	 *
	 * @return ConsoleModel
	 */
	protected function createConsoleNotAllowedModel()
	{
		$viewModel = new ConsoleModel();
		$viewModel->setErrorLevel(1);
		$viewModel->setResult('Page not allowed');
		return $viewModel;
	}

	/**
	 * Get the response
	 *
	 * @return Response
	 * @throws Exception\DomainException if unable to find response
	 */
	protected function getResponse()
	{
		if ($this->response) {
			return $this->response;
		}

		$event    = $this->getEvent();
		$response = $event->getResponse();
		if (!$response instanceof Response) {
			throw new Exception\DomainException('Redirect plugin requires event compose a response');
		}
		$this->response = $response;
		return $this->response;
	}

	/**
	 * Get the event
	 *
	 * @return MvcEvent
	 * @throws Exception\DomainException if unable to find event
	 */
	protected function getEvent()
	{
		if ($this->event) {
			return $this->event;
		}

		$controller = $this->getController();
		if (!$controller instanceof InjectApplicationEventInterface) {
			throw new Exception\DomainException('Not allowed plugin requires a controller that implements InjectApplicationEventInterface');
		}

		$event = $controller->getEvent();
		if (!$event instanceof MvcEvent) {
			$params = $event->getParams();
			$event  = new MvcEvent();
			$event->setParams($params);
		}
		$this->event = $event;

		return $this->event;
	}
}
