<?php
/**
 * @deprecated Use AbstractArrayEntityTableFactory instead
 */

namespace RvBase\Table;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractTableGatewayFactory
 * @package RvBase\Table
 */
abstract class AbstractTableGatewayFactory implements FactoryInterface
{
    /**
     * @var mixed Entity table
     */
    protected $table;

    protected $adapterName = 'Zend\Db\Adapter\Adapter';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get($this->adapterName);

        $entity = $this->createEntityPrototype($serviceLocator);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($entity);

        return new TableGateway(
            $this->table,
            $adapter,
            $this->getFeatures($serviceLocator),
            $resultSetPrototype
        );
    }

    /**
     * Get features for table gateway
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    public function getFeatures(ServiceLocatorInterface $serviceLocator)
    {
        return array();
    }

    /**
     * Create and init entity object for ResultSet prototype
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    abstract public function createEntityPrototype(ServiceLocatorInterface $serviceLocator);
}
