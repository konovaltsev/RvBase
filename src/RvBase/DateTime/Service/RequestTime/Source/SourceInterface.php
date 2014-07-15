<?php

namespace RvBase\DateTime\Service\RequestTime\Source;

/**
 * Interface SourceInterface
 * @package RvBase\DateTime\Service\RequestTimeProvider\Source
 */
interface SourceInterface
{
    /**
     * @return \DateTime
     */
    public function getTime();
}
