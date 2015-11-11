<?php

namespace RvBase\Permissions;

use RvBase\Permissions\Acl\IdentityRoleInitializerInterface;
use RvBase\Permissions\Acl\ResourceInitializerInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\AclInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Класс Permissions
 *
 * @package RvBase\Permissions
 */
class AclPermissions implements PermissionsInterface
{
    /** @var AclInterface */
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
        AclInterface $acl,
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
            $this->getResourceInitializer()->initialize($acl, $resource),
            $privilege
        );
    }

    public function hasResource($resource)
    {
        $acl = $this->getAcl();

        return $acl->hasResource($this->getResourceInitializer()->initialize($acl, $resource));
    }

    /**
     * Получение Acl
     *
     * @return mixed
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
