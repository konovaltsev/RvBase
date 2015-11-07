<?php

namespace RvBase\Entity;

use Zend\InputFilter\InputFilterInterface;
use ArrayAccess;

/**
 * Class ArrayEntity
 * @package RvBase\Entity
 */
class ArrayEntity implements ArrayAccess
{
    protected $data = array();
    protected $changed = array();
    protected $sourceInputFilter;

    /**
     * Lazy load data providers
     *
     * @var callable[]
     */
    protected $lazyLoader = array();

    /**
     * Lazy load data
     *
     * @var array
     */
    protected $lazyData = array();

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

    /**
     * Получение данных из ленивой загрузки
     *
     * @param $field
     */
    public function getLazyField($field)
    {
        if(!array_key_exists($field, $this->lazyData))
        {
            $this->lazyData[$field] = call_user_func($this->lazyLoader[$field], $this);
        }

        return $this->lazyData[$field];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->data, $this->lazyData);
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
     * Set lazy loader for field
     *
     * @param $field
     * @param callable $loader
     * @return $this
     */
    public function setLazyLoader($field, callable $loader)
    {
        $this->lazyLoader[$field] = $loader;
        return $this;
    }

    /**
     * @param array $loaders 'field' => callback
     * @return $this
     */
    public function addLazyLoaders(array $loaders)
    {
        array_walk(
            $loaders,
            function($callback, $field)
            {
                $this->setLazyLoader($field, $callback);
            }
        );
        return $this;
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return static
     */
    public function setSourceInputFilter(InputFilterInterface $inputFilter)
    {
        $this->sourceInputFilter = $inputFilter;
        return $this;
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

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data) || array_key_exists($offset, $this->lazyLoader);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if(array_key_exists($offset, $this->data))
        {
            return $this->getArrayField($offset);
        }

        if(array_key_exists($offset, $this->lazyLoader))
        {
            return $this->getLazyField($offset);
        }

        throw new Exception\InvalidArgumentException(
            sprintf(
                '%s: invalid offset `%s`',
                __METHOD__,
                $offset
            )
        );
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if(!array_key_exists($offset, $this->data))
        {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s: invalid or read only key `%s`',
                    __METHOD__,
                    $offset
                )
            );
        }

        $this->setArrayField($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if(!array_key_exists($offset, $this->lazyLoader))
        {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s: only removing of lazy load data available `%s`',
                    __METHOD__,
                    $offset
                )
            );
        }

        if(array_key_exists($offset, $this->lazyData))
        {
            unset($this->lazyData[$offset]);
        }
    }
}
