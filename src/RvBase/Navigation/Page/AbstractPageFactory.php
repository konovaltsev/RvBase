<?php

namespace RvBase\Navigation\Page;

use Zend\Http\Request;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Navigation\Page\AbstractPage;

/**
 * Class AbstractPageFactory
 * @package RvBase\Navigation\Page
 */
abstract class AbstractPageFactory implements PageFactoryInterface
{
    /** @var RouteMatch */
    private $routeMatch;

    /** @var Router */
    private $router;

    /** @var null */
    private $request;

    public function __construct(
        RouteMatch $routeMatch = null,
        Router $router = null,
        $request = null
    )
    {
        $this->routeMatch   = $routeMatch;
        $this->router       = $router;

        // HTTP request is the only one that may be injected
        if ($request instanceof Request) {
            $this->request = $request;
        }
    }

    abstract protected function getPages($options);

    public function createPage($options)
    {
        $pages = $this->getPages($options);
        $options['pages'] = $this->injectComponents($pages);

        return AbstractPage::factory($options);
    }

    /**
     * @param array $pages
     * @return array
     */
    protected function injectComponents(
        array $pages
    )
    {
        foreach ($pages as &$page) {
            $hasUri = isset($page['uri']);
            $hasMvc = isset($page['action']) || isset($page['controller']) || isset($page['route']);
            if ($hasMvc) {
                if (!isset($page['routeMatch']) && $this->routeMatch) {
                    $page['routeMatch'] = $this->routeMatch;
                }
                if (!isset($page['router'])) {
                    $page['router'] = $this->router;
                }
            } elseif ($hasUri) {
                if (!isset($page['request'])) {
                    $page['request'] = $this->request;
                }
            }

            if (isset($page['pages'])) {
                $page['pages'] = $this->injectComponents($page['pages']);
            }
        }
        return $pages;
    }

    /**
     * @return string
     */
    protected function getMatchedRouteName()
    {
        if($this->routeMatch instanceof RouteMatch)
        {
            return $this->routeMatch->getMatchedRouteName();
        }

        return '';
    }
}
