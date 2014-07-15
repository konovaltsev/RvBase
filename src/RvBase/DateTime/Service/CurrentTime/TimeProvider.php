<?php

namespace RvBase\DateTime\Service\CurrentTime;

/**
 * Class TimeProvider
 * @package RvBase\DateTime\Service\TimeProvider
 */
class TimeProvider implements TimeProviderInterface
{
    protected $source;

    public function __construct(Source\SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @return \DateTime
     */
    public function getCurrentTime()
    {
        return $this->source->getTime();
    }
}
