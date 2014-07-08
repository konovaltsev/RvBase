<?php

namespace RvBase\Entity;

use Zend\InputFilter\InputFilterInterface;

/**
 * Class AbstractArrayEntity
 * @package RvBase\Entity
 */
class AbstractArrayEntity
{
    protected $data = array();
    protected $changed = array();
    protected $sourceInputFilter;

    /**
     * Установка свойства
     *
     * @param string $field
     * @param mixed $value
     * @return static
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
        $filters = $this->getSourceInputFilter();
        if(!empty($filters))
        {
            $data = array_merge($data, $filters->setData($data)->getValues());
        }
        $this->data = $data;
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setSourceInputFilter(InputFilterInterface $inputFilter)
    {
        $this->sourceInputFilter = $inputFilter;
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getSourceInputFilter()
    {
        return $this->sourceInputFilter;
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
