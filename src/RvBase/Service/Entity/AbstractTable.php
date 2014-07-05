<?php

namespace RvBase\Service\Entity;

use Zend\Db\TableGateway\TableGateway;

/**
 * Class AbstractTableGatewayService
 * @package RvBase\Service\Entity
 */
class AbstractTable
{
    protected $tableGateway;
    protected $primaryKey = null;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return null
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getEntity($id)
    {
        $entity = $this->findEntity($id);
        if (!$entity)
        {
            throw new Exception\RuntimeException(
                sprintf(
                    'Could not find entity with id `%s` (%s)',
                    $id,
                    get_class($this)
                )
            );
        }
        return $entity;
    }

    public function findEntity($id)
    {
        $rowset = $this->tableGateway->select(array($this->getPrimaryKey() => $id));
        return $rowset->current();
    }
}
