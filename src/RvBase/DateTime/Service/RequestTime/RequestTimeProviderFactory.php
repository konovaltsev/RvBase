<?php

namespace RvBase\DateTime\Service\RequestTime;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Класс RequestTimeProviderFactory
 *
 * @package RvBase\DateTime\Service\RequestTime
 */
class RequestTimeProviderFactory implements FactoryInterface
{
	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$config = $serviceLocator->get('Config');
		$source = isset($config['rv-base']['time']['request']['source-service'])?
			$serviceLocator->get($config['rv-base']['time']['request']['source-service'])
			: new Source\Server()
		;
		return new RequestTimeProvider($source);
	}
}
