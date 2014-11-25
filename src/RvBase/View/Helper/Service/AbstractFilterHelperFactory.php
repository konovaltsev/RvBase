<?php

namespace RvBase\View\Helper\Service;

use RvBase\View\Helper\FilterHelper;
use Zend\Cache\Storage\StorageInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\FilterInterface;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractFilterHelperFactory
 * @package RvBase\View\Helper\Service
 */
class AbstractFilterHelperFactory implements FactoryInterface
{
    protected $configFilterHelpersKey = 'filter_helpers';
    protected $configKey;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $filtersConfig = $this->getConfig($serviceLocator);

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
                    $this->configKey
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

    protected function createHelper(FilterInterface $filter)
    {
        $helper = new FilterHelper();
        $helper->setFilter($filter);
        return $helper;
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

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
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

        if(!array_key_exists($this->configKey, $config))
        {
            return array();
        }

        return $config[$this->configKey];
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
}
