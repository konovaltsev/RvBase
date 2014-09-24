<?php

namespace RvBase\Table;

use RvBase\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractTableFactory
 * @package RvBase\Table
 */
abstract class AbstractArrayEntityTableFactory implements FactoryInterface
{
    protected $tableGatewayName;

    /**
     * @var mixed Entity table
     */
    protected $table;

    protected $adapterName = 'Zend\Db\Adapter\Adapter';

    protected $tableClass;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
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
        return new $this->tableClass($tableGateway);
    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator)
    {
        $entity = $this->createEntityPrototype($serviceLocator);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($entity);

        return new TableGateway(
            $this->table,
            $this->getAdapter($serviceLocator),
            $this->getFeatures($serviceLocator),
            $resultSetPrototype
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AdapterInterface
     */
    protected function getAdapter(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->adapterName);
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
}
