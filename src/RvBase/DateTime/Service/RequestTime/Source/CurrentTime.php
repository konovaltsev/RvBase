<?php

namespace RvBase\DateTime\Service\RequestTime\Source;
use RvBase\DateTime\Service\CurrentTime\TimeProviderInterface;

/**
 * Class TimeProvider
 * @package RvBase\DateTime\Service\RequestTimeProvider\Source
 */
class CurrentTime implements SourceInterface
{
	protected static $time;

    public function __construct(TimeProviderInterface $timeProvider)
    {
		static::$time = $timeProvider->getCurrentTime();
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return clone self::$time;
    }
}
