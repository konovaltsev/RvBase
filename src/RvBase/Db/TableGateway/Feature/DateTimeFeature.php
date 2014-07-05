<?php

namespace RvBase\Db\TableGateway\Feature;

use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\TableGateway\Feature\AbstractFeature;

/**
 * Class DateTimeFeature
 * @package RvBase\Db\TableGateway\Feature
 */
class DateTimeFeature extends AbstractFeature
{
    protected $columns = array();
    protected $format;

    public function __construct($columns, $format=\DateTime::ISO8601)
    {
        $this->columns = $columns;
        $this->format = $format;
    }

    public function preInsert(Insert $insert)
    {
        foreach($this->columns as $c)
        {
            $v = $insert->$c;
            if($v instanceof \DateTime)
            {
                $insert->$c = $v->format($this->format);
            }
        }

        return $insert;
    }

    public function preUpdate(Update $update)
    {
        $set = $update->getRawState('set');
        foreach($this->columns as $c)
        {
            if(array_key_exists($c, $set) && ($set[$c] instanceof \DateTime))
            {
                $update->set(array($c => $set[$c]->format($this->format)), $update::VALUES_MERGE);
            }
        }
    }
}
