<?php

namespace RvBase\View\Helper\Service;

use RvBase\View\Helper\FilterHelper;
use Zend\Cache\Storage\StorageInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\FilterInterface;
use Zend\Filter\FilterPluginManager;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FilterHelperAbstractFactory
 * @package RvBase\View\Helper\Service
 */
class FilterHelperAbstractFactory implements AbstractFactoryInterface
{
    protected $configFilterHelpersKey = 'filter_helpers';

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator, $requestedName);
        return !empty($config);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $filtersConfig = $this->getConfig($serviceLocator, $requestedName);

        if(array_key_exists('name', $filtersConfig))
        {
            $helper = $this->createHelper($this->getFilter($serviceLocator, $filtersConfig));
        }
        elseif(array_key_exists('chain', $filtersConfig))
        {
            $helper = $this->createHelper($this->getFilterChain($serviceLocator, $filtersConfig['chain']));
        }
        else
        {
            throw new Exception\ServiceNotCreatedException(
                sprintf(
                    'Invalid config for `%s` filter view helper',
                    $requestedName
                )
            );
        }

        if(array_key_exists('cache', $filtersConfig))
        {
            $helper->setCacheStorage($this->getCacheStorage($serviceLocator, $filtersConfig));
        }
        if(array_key_exists('cache_tags', $filtersConfig))
        {
            $helper->setCacheTags($filtersConfig['cache_tags']);
        }

        return $helper;
    }

    protected function getConfigKey($requestedName)
    {
        $filter = new CamelCaseToDash();
        $configKey = strtolower($filter->filter($requestedName));

        return $configKey;
    }

    protected function createHelper(FilterInterface $filter)
    {
        $helper = new FilterHelper();
        $helper->setFilter($filter);
        return $helper;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $params
     * @return FilterInterface
     */
    protected function getFilter(ServiceLocatorInterface $serviceLocator, $params)
    {
        $name = $params['name'];
        $options = array_key_exists('options', $params)? $params['options'] : array();

        return $this->getFilterManager($serviceLocator)->get($name, $options);
    }

    protected function getFilterChain(ServiceLocatorInterface $serviceLocator, $options)
    {
        $filterChain = new FilterChain();
        $filterChain->setPluginManager($this->getFilterManager($serviceLocator));
        $filterChain->setOptions($options);

        return $filterChain;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return FilterPluginManager
     */
    protected function getFilterManager(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator instanceof ServiceLocatorAwareInterface)
        {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $filters = $serviceLocator->get('FilterManager');
        return $filters;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $config
     * @return StorageInterface
     */
    protected function getCacheStorage(ServiceLocatorInterface $serviceLocator, array $config)
    {
        if($serviceLocator instanceof ServiceLocatorAwareInterface)
        {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        if(array_key_exists('cache', $config))
        {
            return $serviceLocator->get($config['cache']);
        }

        return null;
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator, $requestedName)
    {
        if($serviceLocator instanceof ServiceLocatorAwareInterface)
        {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $serviceLocator->get('Config');

        if(!array_key_exists('rv-base', $config))
        {
            return array();
        }
        $config = $config['rv-base'];

        if(!array_key_exists($this->configFilterHelpersKey, $config))
        {
            return array();
        }
        $config = $config[$this->configFilterHelpersKey];

        $configKey = $this->getConfigKey($requestedName);
        if(!array_key_exists($configKey, $config))
        {
            return array();
        }

        return $config[$configKey];
    }
}
