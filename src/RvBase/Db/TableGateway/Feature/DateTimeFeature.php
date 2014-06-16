<?php

namespace RvBase\Db\TableGateway\Feature;

use Zend\Db\Sql\Insert;
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
}
