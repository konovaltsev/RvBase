<?php

namespace RvBase\DateTime\Service\CurrentTime\Source;

/**
 * Interface SourceInterface
 * @package RvBase\DateTime\Service\TimeProvider\Source
 */
interface SourceInterface
{
    /**
     * @return \DateTime
     */
    public function getTime();
}
