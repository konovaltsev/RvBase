<?php

namespace RvBase\DateTime\Service\RequestTime;


interface RequestTimeProviderInterface
{
    /**
     * @return \DateTime
     */
    public function getRequestTime();
}
