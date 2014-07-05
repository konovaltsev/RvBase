<?php

namespace RvBase\Entity;

/**
 * Class AbstractArrayEntity
 * @package MagicTower\Entity
 */
class AbstractArrayEntity
{
    protected $data = array();
    protected $changed = array();

    /**
     * Установка свойства
     *
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function setArrayField($field, $value)
    {
        $this->data[$field] = $value;
        $this->changed[$field] = $field;
        return $this;
    }

    /**
     * Получение свойства
     *
     * @param mixed $field
     * @return mixed
     * @throws Exception\InvalidArgumentException
     */
    public function getArrayField($field)
    {
        if(!array_key_exists($field, $this->data))
        {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Undefined field `%s` for entity `%s`',
                    $field,
                    get_class($this)
                )
            );
        }
        return $this->data[$field];
    }

    public function exchangeArray($data)
    {
        $this->data = $data;
    }

    public function getArrayData()
    {
        return $this->data;
    }

    public function getArrayChangedFields()
    {
        return $this->changed;
    }

    public function clearChanged()
    {
        $this->changed = array();
    }
}
