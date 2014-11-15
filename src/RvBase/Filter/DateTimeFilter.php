<?php

namespace RvBase\Filter;

use DateTime;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Class DateTime
 * @package RvBase\Filter
 */
class DateTimeFilter extends AbstractFilter
{
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return DateTime|mixed
     */
    public function filter($value)
    {
        try {
            $result = $this->normalizeDateTime($value);
        } catch (\Exception $e) {
            // DateTime threw an exception, an invalid date string was provided
            throw new Exception\InvalidArgumentException('Invalid date string provided', $e->getCode(), $e);
        }

        if ($result === false) {
            return $value;
        }

        return $result;
    }

    /**
     * Normalize the provided value to a formatted string
     *
     * @param  string|int|\DateTime $value
     * @return DateTime
     */
    protected function normalizeDateTime($value)
    {
        if ($value === '' || $value === null) {
            return $value;
        }

        if (!is_string($value) && !is_int($value) && !$value instanceof \DateTime) {
            return $value;
        }

        if (is_int($value)) {
            //timestamp
            $value = new DateTime('@' . $value);
        }
        elseif (!$value instanceof \DateTime) {
            $value = new DateTime($value);
        }

        return $value;
    }
}
