<?php

namespace RvBase\Permissions;

use RvBase\Permissions\Acl\IdentityRoleInitializerInterface;
use RvBase\Permissions\Acl\ResourceInitializerInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Exception;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Класс Permissions
 *
 * @package RvBase\Permissions
 */
class AclPermissions implements AclPermissionsInterface
{
    /** @var Acl */
    private $acl;

    /** @var AuthenticationServiceInterface */
    private $authenticationService;

    /** @var IdentityRoleInitializerInterface */
    private $identityRoleInitializer;

    /** @var ResourceInitializerInterface */
    private $resourceInitializer;

    /** @var null|mixed */
    private $identity = false;

    /** @var null|string|RoleInterface */
    private $identityRole = false;

    public function __construct(
        Acl $acl,
        AuthenticationServiceInterface $authenticationService,
        IdentityRoleInitializerInterface $identityRoleInitializer,
        ResourceInitializerInterface $resourceInitializer
    )
    {
        $this->acl = $acl;
        $this->authenticationService = $authenticationService;
        $this->identityRoleInitializer = $identityRoleInitializer;
        $this->resourceInitializer = $resourceInitializer;
    }

    public function isAllowed($resource, $privilege = null)
    {
        $acl = $this->getAcl();

        return $acl->isAllowed(
            $this->getIdentityRole(),
            $this->getResource($resource),
            $privilege
        );
    }

    public function hasResource($resource)
    {
        $acl = $this->getAcl();

        $aclResource = $this->findResource($resource);
        if(!(is_string($aclResource) || ($aclResource instanceof ResourceInterface)))
        {
            return false;
        }

        return $acl->hasResource($aclResource);
    }

    public function getResource($resource)
    {
        $aclResource = $this->findResource($resource);
        if(!$aclResource)
        {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Resource for `%s` not found',
                    (
                        is_object($resource)
                            ? get_class($resource)
                            : (is_scalar($resource)? sprintf('%s[%s]', gettype($resource), $resource) : gettype($resource))
                    )
                )
            );
        }

        return $aclResource;
    }

    public function findResource($resource)
    {
        return $this->getResourceInitializer()->initialize($this->getAcl(), $resource);
    }

    /**
     * Получение Acl
     *
     * @return Acl
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * Получение AuthenticationService
     *
     * @return mixed
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    private function getIdentityRole()
    {
        if ($this->identityRole === false) {
            $this->identityRole = $this->getIdentityRoleInitializer()->initialize($this->getAcl(), $this->getIdentity());
        }

        return $this->identityRole;
    }

    /**
     * Получение IdentityRoleInitializer
     *
     * @return IdentityRoleInitializerInterface
     */
    private function getIdentityRoleInitializer()
    {
        return $this->identityRoleInitializer;
    }

    private function getIdentity()
    {
        if ($this->identity === false) {
            $authenticationService = $this->getAuthenticationService();
            $this->identity = $authenticationService->hasIdentity() ? $authenticationService->getIdentity() : null;
        }

        return $this->identity;
    }

    /**
     * Получение ResourceInitializer
     *
     * @return ResourceInitializerInterface
     */
    private function getResourceInitializer()
    {
        return $this->resourceInitializer;
    }
}
