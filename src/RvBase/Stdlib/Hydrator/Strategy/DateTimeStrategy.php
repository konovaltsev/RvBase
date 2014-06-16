<?php

namespace RvBase\Stdlib\Hydrator\Strategy;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Class DateTimeStrategy
 * @package RvBase\Stdlib\Hydrator\Strategy
 */
class DateTimeStrategy implements StrategyInterface
{
    protected $format;
    protected $defaultFormat = \DateTime::ISO8601;

    public function __construct($format = null)
    {
        $this->format = $format;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @param object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if($value instanceof \DateTime)
        {
            return $value->format($this->format ?: $this->defaultFormat);
        }
        return $value;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if(!($value instanceof \DateTime))
        {
            return $this->format? \DateTime::createFromFormat($this->format, $value) : new \DateTime($value);
        }
        return $value;
    }
}
