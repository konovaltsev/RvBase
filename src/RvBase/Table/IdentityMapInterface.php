<?php

namespace RvBase\Table;

/**
 * Interface IdentityMapInterface
 * @package RvBase\Table
 */
interface IdentityMapInterface
{
    /**
     * @param object $entity
     * @return object
     */
    public function get($entity);

    /**
     * Drop entity from identity map
     *
     * @param $entity
     */
    public function reset($entity);
}
