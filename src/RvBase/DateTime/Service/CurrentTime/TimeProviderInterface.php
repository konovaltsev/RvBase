<?php

namespace RvBase\DateTime\Service\CurrentTime;


interface TimeProviderInterface
{
    /**
     * @return \DateTime
     */
    public function getCurrentTime();
}
