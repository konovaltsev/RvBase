<?php

namespace RvBase\Table;

use RvBase\Entity\ArrayEntity;

/**
 * Class ArrayEntityIdentityMap
 * @package RvBase\Table
 */
class ArrayEntityIdentityMap implements IdentityMapInterface
{
    protected $map = array();

    protected $primaryKey;

    /**
     * @param mixed $primaryKey
     */
    public function __construct($primaryKey)
    {
        if(is_array($primaryKey))
        {
            $primaryKey = array_combine($primaryKey, $primaryKey);
        }
        $this->primaryKey = $primaryKey;
    }

    /**
     * @param ArrayEntity $entity
     * @return object
     */
    public function get($entity)
    {
        if(!$entity instanceof ArrayEntity)
        {
            throw new Exception\InvalidArgumentException('Entity must be an instance of ArrayEntity');
        }

        $key = $this->getKey($entity->getArrayData());
        if(!$this->exists($key))
        {
            $this->storeEntityToMap($key, $entity);
            return $entity;
        }

        return $this->getEntityFromMap($key);
    }

    /**
     * @param ArrayEntity $entity
     */
    public function reset($entity)
    {
        if(!$entity instanceof ArrayEntity)
        {
            throw new Exception\InvalidArgumentException('Entity must be an instance of ArrayEntity');
        }

        $key = $this->getKey($entity->getArrayData());
        if($this->exists($key))
        {
            $this->dropEntityFromMap($key);
        }
    }

    public function getKey($data)
    {
        if(!is_array($this->primaryKey))
        {
            return $data[$this->primaryKey];
        }

        return implode('___', array_intersect_key($data, $this->primaryKey));
    }

    public function exists($key)
    {
        if(is_array($key))
        {
            $key = $this->getKey($key);
        }
        return isset($this->map[$key]);
    }

    protected function storeEntityToMap($key, ArrayEntity $entity)
    {
        if(is_array($key))
        {
            $key = $this->getKey($key);
        }
        $this->map[$key] = $entity;
        return $this;
    }

    public function getEntityFromMap($key)
    {
        if(is_array($key))
        {
            $key = $this->getKey($key);
        }
        return $this->map[$key];
    }

    protected function dropEntityFromMap($key)
    {
        if(is_array($key))
        {
            $key = $this->getKey($key);
        }
        unset($this->map[$key]);
        return $this;
    }
}
