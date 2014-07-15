<?php

namespace RvBase\DateTime\Service\CurrentTime\Source;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Sql;

/**
 * Class DbAdapter
 * @package RvBase\DateTime\Service\TimeProvider\Source
 */
class DbAdapter implements SourceInterface
{
    protected $dbAdapter;

    public function __construct(Adapter $adapter)
    {
        $this->dbAdapter = $adapter;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->columns(
            array(
                'now' => new Expression('NOW()'),
            )
        );

        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        $d = new \DateTime($results->current()['now']);
        $d->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $d;
    }
}
