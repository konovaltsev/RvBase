<?php

namespace RvBase\DateTime\Service\CurrentTime;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Класс TimeProviderFactory
 *
 * @package RvBase\DateTime\Service\CurrentTime
 */
class TimeProviderFactory implements FactoryInterface
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
		$source = isset($config['rv-base']['time']['current']['source-service'])?
			$serviceLocator->get($config['rv-base']['time']['current']['source-service'])
			: new Source\Server()
		;
		return new TimeProvider($source);
	}
}
