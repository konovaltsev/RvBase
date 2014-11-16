<?php

namespace RvBase\View\Helper;

use Zend\Filter\FilterInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class FilterHelper
 * @package RvBase\View\Helper
 */
class FilterHelper extends AbstractHelper
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    public function __invoke($value)
    {
        return $this->render($value);
    }

    public function render($value)
    {
        return $this->filter->filter($value);
    }

    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;
        return $this;
    }
}
