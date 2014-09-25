<?php

namespace RvBase\ServiceFactory\Table;

use RvBase\Db\ResultSet\ResultSet;
use RvBase\Table\AbstractArrayEntityTable;
use RvBase\Table\ArrayEntityIdentityMap;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractTableFactory
 * @package RvBase\Table
 */
abstract class AbstractArrayEntityTableFactory implements FactoryInterface
{
    protected $configKey;

    protected $defaultAdapter = 'Zend\Db\Adapter\Adapter';

    protected $config;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->config = $this->getConfig($serviceLocator);
        $tableGateway = $this->createTableGateway($serviceLocator);
        $table = $this->createTable($tableGateway, $serviceLocator);

        /** @var ResultSet $resultSet */
        $resultSet = $tableGateway->getResultSetPrototype();

        $identityMap = new ArrayEntityIdentityMap($table->getPrimaryKey());
        $table->setIdentityMap($identityMap);
        $resultSet->setIdentityMap($identityMap);

        return $table;
    }

    /**
     * @param TableGateway $tableGateway
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractArrayEntityTable
     */
    protected function createTable(TableGateway $tableGateway, ServiceLocatorInterface $serviceLocator)
    {
        $tableClass = $this->getTableClass($serviceLocator);
        return new $tableClass($tableGateway);
    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator)
    {
        $entity = $this->createEntityPrototype($serviceLocator);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($entity);

        return new TableGateway(
            $this->getTable(),
            $this->getAdapter($serviceLocator),
            $this->getFeatures($serviceLocator),
            $resultSetPrototype
        );
    }

    protected function getTable()
    {
        $table = $this->getTableName();
        $schema = $this->getSchema();
        if($schema)
        {
            $table = new TableIdentifier($table, $schema);
        }
        return $table;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AdapterInterface
     */
    protected function getAdapter(ServiceLocatorInterface $serviceLocator)
    {
        $adapterName = $this->getAdapterName($serviceLocator);
        return $serviceLocator->get($adapterName);
    }

    /**
     * Get features for table gateway
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getFeatures(ServiceLocatorInterface $serviceLocator)
    {
        return array();
    }

    /**
     * Create and init entity object for ResultSet prototype
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    abstract protected function createEntityPrototype(ServiceLocatorInterface $serviceLocator);

    protected function getTableClass()
    {
        if(!isset($this->config['class']))
        {
            throw new Exception\RuntimeException(
                sprintf(
                    '%s::%s: table class name does not exists in table config with key `%s`',
                    get_class($this),
                    __FUNCTION__,
                    $this->configKey
                )
            );
        }

        return $this->config['class'];
    }

    protected function getAdapterName()
    {
        return
            isset($this->config['db-adapter'])?
                $this->config['db-adapter']
                : $this->defaultAdapter
        ;
    }

    protected function getSchema()
    {
        return
            isset($this->config['schema'])?
                $this->config['schema']
                : null
            ;
    }

    protected function getTableName()
    {
        if(!isset($this->config['name']))
        {
            throw new Exception\RuntimeException(
                sprintf(
                    '%s::%s: table name does not exists in table config with key `%s`',
                    get_class($this),
                    __FUNCTION__,
                    $this->configKey
                )
            );
        }
        return $this->config['name'];
    }

    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config'))
        {
            return array();
        }

        $config = $serviceLocator->get('Config');
        if(!isset($config['rv-base']['db']['tables'][$this->configKey]) || !is_array($config['rv-base']['db']['tables'][$this->configKey]))
        {
            return array();
        }

        return $config['rv-base']['db']['tables'][$this->configKey];
    }
}
