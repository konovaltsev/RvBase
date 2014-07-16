<?php

namespace RvBase\DateTime\Service\RequestTime\Source;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Класс CurrentTimeSourceFactory
 *
 * @package RvBase\DateTime\Service\RequestTime\Source
 */
class CurrentTimeSourceFactory implements FactoryInterface
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
		$ctServiceName = isset($config['rv-base']['time']['request']['source']['current-time-service'])?
			$config['rv-base']['time']['request']['source']['current-time-service']
			: 'rv-base.time.current'
		;

		return new CurrentTime($serviceLocator->get($ctServiceName));
	}
}
