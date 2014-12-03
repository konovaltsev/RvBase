<?php

namespace RvBase\Table;

use RvBase\Entity\ArrayEntity;
use RvBase\Table\Exception;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class AbstractArrayTableGatewayService
 * @package RvBase\Table
 */
class AbstractArrayEntityTable
{
    protected $tableGateway;

    protected $primaryKey = null;

    /** @var ArrayEntityIdentityMap */
    protected $identityMap;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return string|array
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param $id
     * @return ArrayEntity
     */
    public function getEntity($id)
    {
        $entity = $this->findEntity($id);
        if (!$entity)
        {
            throw new Exception\RuntimeException(
                sprintf(
                    'Could not find entity with id `%s` (%s)',
                    var_export($id, true),
                    get_class($this)
                )
            );
        }
        return $entity;
    }

    public function findEntity($id)
    {
        if($this->identityMap instanceof ArrayEntityIdentityMap && $this->identityMap->exists($id))
        {
            return $this->identityMap->getEntityFromMap($id);
        }
        $rowSet = $this->getTableGateway()->select($this->getIdFieldsFromData($id));

        return $rowSet->current();
    }

	/**
	 * Update
	 *
	 * @param ArrayEntity $entity
	 * @return int
	 */
    public function saveEntity(ArrayEntity $entity)
    {
        $data = $entity->getArrayData();

        $primary = $this->getIdFieldsFromData($data);

        $data = array_intersect_key($data, $entity->getArrayChangedFields());
        if(empty($data))
        {
            return 0;
        }

        $result = $this->getTableGateway()->update(
            $data,
            $primary
        );

        $entity->clearChanged();

        return $result;
    }

	/**
	 * Create
	 *
	 * @param $data
	 * @return ArrayEntity
	 */
	public function createEntity($data)
	{
		if(empty($data) || !is_array($data))
		{
			throw new Exception\InvalidArgumentException('Data is empty or not array');
		}

		$tableGateway = $this->getTableGateway();
		$result = $tableGateway->insert($data);
		if(empty($result))
		{
			throw new Exception\RuntimeException('Data not inserted');
		}

		$primaryKey = $this->getPrimaryKey();
        if(is_array($primaryKey) && count($primaryKey) == 1)
        {
            $primaryKey = array_pop($primaryKey);
        }
		if(!is_array($primaryKey))
		{
			$lastInsertValue = $tableGateway->getLastInsertValue();
			if(!empty($lastInsertValue))
			{
				$data[$primaryKey] = $lastInsertValue;
			}
		}

        $entity = $this->getEntity($data);

        return $entity;
	}

    /**
     * @return TableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @return \Zend\Db\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->getTableGateway()->getAdapter();
    }

    /**
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql()
    {
        return $this->getTableGateway()->getSql();
    }

    /**
     * @return string|TableIdentifier
     */
    public function getTable()
    {
        return $this->getTableGateway()->getTable();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        $table = $this->getTable();
        if($table instanceof TableIdentifier)
        {
            return $table->getTable();
        }
        return $table;
    }

    /**
     * @return string
     */
    public function getTableFullName()
    {
        $table = $this->getTable();
        if($table instanceof TableIdentifier)
        {
            if($table->hasSchema())
            {
                return $table->getSchema() . $this->getIdentifierSeparator() . $table->getTable();
            }
            return $table->getTable();
        }
        return $table;
    }

    public function getIdentifierSeparator()
    {
        return $this->getTableGateway()->getAdapter()->getPlatform()->getIdentifierSeparator();
    }

    public function getColumnFullName($columnName)
    {
        return $this->getTableFullName() . $this->getIdentifierSeparator() . $columnName;
    }

    protected function getIdFieldsFromData($data)
    {
        $primary = $this->getPrimaryKey();
        if(!is_array($primary))
        {
            if(!is_array($data))
            {
                $data = array($primary => $data);
            }
            $primary = array($primary);
        }
        $id = array();
        foreach($primary as $field)
        {
            $id[$this->getColumnFullName($field)] = $data[$field];
        }
        return $id;
    }

    /**
     * @param ArrayEntityIdentityMap $identityMap
     * @return $this
     */
    public function setIdentityMap(ArrayEntityIdentityMap $identityMap)
    {
        $this->identityMap = $identityMap;
        return $this;
    }
}
