<?php

namespace RvBase\DateTime\Service\RequestTime\Source;
use RvBase\DateTime\Service\CurrentTime\TimeProviderInterface;

/**
 * Class TimeProvider
 * @package RvBase\DateTime\Service\RequestTimeProvider\Source
 */
class CurrentTime implements SourceInterface
{
    protected $timeProvider;

    public function __construct(TimeProviderInterface $timeProvider)
    {
        $this->timeProvider = $timeProvider;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->timeProvider->getCurrentTime();
    }
}
