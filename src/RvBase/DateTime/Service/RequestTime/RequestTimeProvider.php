<?php

namespace RvBase\DateTime\Service\RequestTime;

/**
 * Class RequestTimeProvider
 * @package RvBase\DateTime\Service\RequestTimeProvider
 */
class RequestTimeProvider implements RequestTimeProviderInterface
{
    protected $source;
    protected $time;

    public function __construct(Source\SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @return \DateTime
     */
    public function getRequestTime()
    {
        if($this->time === null)
        {
            $this->time = $this->source->getTime();
        }
        return clone $this->time;
    }
}
