<?php

namespace RvBase\View\Helper;

use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\Storage\TaggableInterface;
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

    /**
     * @var null|StorageInterface
     */
    protected $cacheStorage;

    protected $cacheTags = array('filter_helper');

    public function __invoke($value)
    {
        return $this->render($value);
    }

    public function render($value)
    {
        $storage = $this->cacheStorage;
        if($storage instanceof StorageInterface)
        {
            $key = $this->getCacheKey($value);
            if($storage->hasItem($key))
            {
                return $storage->getItem($key);
            }

            $value = $this->filter->filter($value);
            $storage->setItem($key, $value);
            if($storage instanceof TaggableInterface)
            {
                $storage->setTags(
                    $key,
                    $this->getCacheTags()
                );
            }

            return $value;
        }

        return $this->filter->filter($value);
    }

    /**
     * @return array
     */
    protected function getCacheTags()
    {
        if(empty($this->cacheTags))
        {
            $this->cacheTags = array('filter_helper');
        }
        return $this->cacheTags;
    }

    /**
     * @param array $cacheTags
     * @return FilterHelper
     */
    public function setCacheTags(array $cacheTags)
    {
        $tags = array_unique(
            array_merge(
                array('filter_helper'),
                $cacheTags
            )
        );
        $this->cacheTags = $tags;
        return $this;
    }

    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @param StorageInterface $cacheStorage
     * @return FilterHelper
     */
    public function setCacheStorage(StorageInterface $cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
        return $this;
    }

    protected function getCacheKey($value)
    {
        $hash = md5($value);
        return 'filter-helper-' . $hash;
    }
}
