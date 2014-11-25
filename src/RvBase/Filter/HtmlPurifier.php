<?php

namespace RvBase\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Class HtmlPurifier
 * @package RvBase\Filter
 */
class HtmlPurifier extends AbstractFilter
{
    /** @var \HTMLPurifier */
    protected $purifier;

    protected $config = array();
    protected $schema = null;
    protected $initialized = false;
    protected $initDefaultUriHost = false;
    protected static $defaultUriHost = null;

    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * @return null
     */
    public static function getDefaultUriHost()
    {
        return self::$defaultUriHost;
    }

    /**
     * @param null $defaultUriHost
     */
    public static function setDefaultUriHost($defaultUriHost)
    {
        self::$defaultUriHost = $defaultUriHost;
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
        return $this->getPurifier()->purify($value);
    }

    public function setConfig($config)
    {
        if($this->initialized)
        {
            throw new Exception\RuntimeException('Can not set config on initialized filter');
        }

        $this->config = $config;
        return $this;
    }

    /**
     * @param null $schema
     * @return HtmlPurifier
     */
    public function setSchema($schema)
    {
        if($this->initialized)
        {
            throw new Exception\RuntimeException('Can not set schema on initialized filter');
        }

        $this->schema = $schema;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInitDefaultUriHost()
    {
        return $this->initDefaultUriHost;
    }

    /**
     * @param boolean $initUriHost
     * @return HtmlPurifier
     */
    public function setInitDefaultUriHost($initUriHost)
    {
        $this->initDefaultUriHost = (boolean)$initUriHost;
        return $this;
    }

    /**
     * @param \HTMLPurifier $purifier
     * @return $this
     */
    public function setPurifier(\HTMLPurifier $purifier)
    {
        if($this->initialized)
        {
            throw new Exception\RuntimeException('Filter already contains purifier');
        }

        $this->purifier = $purifier;
        $this->initialized = true;
        return $this;
    }

    /**
     * @return \HTMLPurifier
     */
    protected function getPurifier()
    {
        if(!$this->initialized)
        {
            $this->setPurifier($this->createPurifier());
        }

        return $this->purifier;
    }

    /**
     * @return \HTMLPurifier
     */
    protected function createPurifier()
    {
        $config = \HTMLPurifier_Config::create($this->config, $this->schema);

        if($this->isInitDefaultUriHost())
        {
            $config->set('URI.Host', self::getDefaultUriHost());
        }

        $purifier = new \HTMLPurifier($config);

        return $purifier;
    }
}
