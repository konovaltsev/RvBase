<?php

namespace RvBase\View\Helper\Service;

use RvBase\View\Helper\FilterHelper;
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
            return $this->createHelper($this->getFilter($serviceLocator, $filtersConfig));
        }

        if(array_key_exists('chain', $filtersConfig))
        {
            return $this->createHelper($this->getFilterChain($serviceLocator, $filtersConfig['chain']));
        }

        throw new Exception\ServiceNotCreatedException(
            sprintf(
                'Invalid config for `%s` filter view helper',
                $this->configKey
            )
        );
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
