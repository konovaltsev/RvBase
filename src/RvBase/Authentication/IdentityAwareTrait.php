<?php

namespace RvBase\Authentication;

/**
 * Class IdentityAwareTrait
 * @package RvBase\Authentication
 */
trait IdentityAwareTrait
{
    protected $identity;

    /**
     * @param mixed $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }
}
