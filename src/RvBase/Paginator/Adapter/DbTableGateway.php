<?php

namespace RvBase\Paginator\Adapter;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;

/**
 * Class DbTableGateway
 * @package RvBase\Paginator\Adapter
 */
class DbTableGateway extends DbSelect
{
    public function __construct(TableGateway $tableGateway, $where = null, $order = null, $join = null, $group = null, $having = null)
    {
        $sql    = $tableGateway->getSql();
        $select = $sql->select();
        if ($where) {
            $select->where($where);
        }
        if ($order) {
            $select->order($order);
        }
        if($join) {
            $select->join(
                $join['name'],
                $join['on'],
                [],
                $join['type']
            );
        }
        if ($group) {
            $select->group($group);
        }
        if ($having) {
            $select->having($having);
        }

        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        parent::__construct($select, $sql, $resultSetPrototype);
    }
}
