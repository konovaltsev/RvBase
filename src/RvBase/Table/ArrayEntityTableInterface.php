<?php

namespace RvBase\Table;

use RvBase\Entity\ArrayEntity;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Paginator;

/**
 * Interface ArrayEntityTableInterface
 * @package RvBase\Table
 */
interface ArrayEntityTableInterface
{
    /**
     * @return string|array
     */
    public function getPrimaryKey();

    /**
     * @param mixed $id
     * @return ArrayEntity
     */
    public function getEntity($id);

    /**
     * @param mixed $id
     * @return ArrayEntity
     */
    public function findEntity($id);

    /**
     * @param Where|\Closure|string|array $where
     * @param bool|false                  $paginated
     * @return ResultSet|Paginator
     */
    public function find($where, $paginated = false);

    /**
     * Update
     *
     * @param ArrayEntity $entity
     * @return int
     */
    public function saveEntity(ArrayEntity $entity);

    /**
     * @param ArrayEntity $entity
     * @return int
     */
    public function deleteEntity(ArrayEntity $entity);

    /**
     * Create
     *
     * @param $data
     * @return ArrayEntity
     */
    public function createEntity($data);

    /**
     * @return TableGateway
     */
    public function getTableGateway();

    /**
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql();

    /**
     * @return string|TableIdentifier
     */
    public function getTable();

    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return string
     */
    public function getTableFullName();

    /**
     * @param string $columnName
     * @return string
     */
    public function getColumnFullName($columnName);

    /**
     * @param string $columnName
     * @return string
     */
    public function getQuotedColumnFullName($columnName);
}
