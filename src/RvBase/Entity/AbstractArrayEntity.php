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
}
