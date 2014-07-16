<?php

namespace RvBase\DateTime\Service\CurrentTime\Source;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Класс DbAdapterSourceFactory
 *
 * @package RvBase\DateTime\Service\CurrentTime\Source
 */
class DbAdapterSourceFactory implements FactoryInterface
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
		$adapterServiceName = isset($config['rv-base']['time']['current']['source']['adapter'])?
			$config['rv-base']['time']['current']['source']['adapter']
			: 'Zend\Db\Adapter\Adapter'
		;

		return new DbAdapter($serviceLocator->get($adapterServiceName));
	}
}
