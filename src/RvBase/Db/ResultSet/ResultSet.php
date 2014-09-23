<?php

namespace RvBase\Db\ResultSet;

use RvBase\Table\IdentityMapInterface;
use Zend\Db\ResultSet\ResultSet as ZendResultSet;

/**
 * Class ResultSet
 * @package RvBase\Db\ResultSet
 */
class ResultSet extends ZendResultSet
{
    /** @var IdentityMapInterface */
    protected $identityMap;

    public function current()
    {
        $object = parent::current();
        if(is_object($object))
        {
            $object = $this->identityMap->get($object);
        }

        return $object;
    }

    /**
     * @param IdentityMapInterface $identityMap
     * @return ResultSet
     */
    public function setIdentityMap(IdentityMapInterface $identityMap)
    {
        $this->identityMap = $identityMap;
        return $this;
    }
}
