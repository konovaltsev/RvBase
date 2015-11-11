<?php

namespace RvBase\Permissions\Acl;

use RvBase\Permissions\Acl\Resource\ResourceProviderInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class ResourceInitializer
 * @package RvBase\Permissions\Acl
 */
class ResourceInitializer implements ResourceInitializerInterface
{
    /** @var ResourceProviderInterface */
    protected $resourceProvider;

    public function __construct(ResourceProviderInterface $resourceProvider)
    {
        $this->resourceProvider = $resourceProvider;
    }

    /**
     * @param Acl $acl
     * @param mixed $resource
     * @return ResourceInterface|string|mixed
     */
    public function initialize(Acl $acl, $resource)
    {
        $aclResource = $this->resourceProvider->getResource($resource);
        if ($aclResource === false) {
            return null;
        }

        if (!$acl->hasResource($aclResource)) {
            $parentResource = $this->resourceProvider->getParentResource($resource);
            if ($parentResource === false) {
                return null;
            }

            $acl->addResource($aclResource, $parentResource);
        }

        return $aclResource;
    }
}
