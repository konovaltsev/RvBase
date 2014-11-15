<?php

namespace RvBase\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Class EmTypograph
 * @package RvBase\Filter
 */
class EmTypograph extends AbstractFilter
{
    /** @var \EMTypograph */
    protected $typograph;

    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        $typograph = $this->getTypograph();
        $typograph->set_text($value);
        return $typograph->apply();
    }

    /**
     * @return \EMTypograph
     */
    protected function getTypograph()
    {
        if($this->typograph === null)
        {
            require_once __DIR__ . '/../../../libs/emuravjev/mdash/EMT.php';
            $this->typograph = new \EMTypograph();
        }
        return $this->typograph;
    }

    /**
     * @param \EMTypograph $typograph
     * @return EmTypograph
     */
    public function setTypograph($typograph)
    {
        $this->typograph = $typograph;
        return $this;
    }

    public function setSetup($setup)
    {
        if(!empty($setup))
        {
            $this->getTypograph()->setup($setup);
        }
    }
}
