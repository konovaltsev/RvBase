<?php

namespace RvBase\DateTime\Service\CurrentTime\Source;

/**
 * Class Server
 * @package RvBase\DateTime\Service\TimeProvider\Source
 */
class Server implements SourceInterface
{
    /**
     * @return \DateTime
     */
    public function getTime()
    {
        $d = \DateTime::createFromFormat('U.u', sprintf('%.06f', microtime(true)));
        $d->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $d;
    }
}
