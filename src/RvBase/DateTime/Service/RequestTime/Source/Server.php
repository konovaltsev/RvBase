<?php

namespace RvBase\DateTime\Service\RequestTime\Source;

/**
 * Class Server
 * @package RvBase\DateTime\Service\RequestTimeProvider\Source
 */
class Server implements SourceInterface
{
    /**
     * @return \DateTime
     */
    public function getTime()
    {
        $requestTime = (PHP_VERSION_ID >= 50400)
            ? $_SERVER['REQUEST_TIME_FLOAT']
            : $_SERVER['REQUEST_TIME'];
        if(is_float($requestTime))
        {
            $d = \DateTime::createFromFormat('U.u', sprintf('%.06f', $requestTime));
        }
        else
        {
            $d = new \DateTime('@' . $requestTime);
        }
        $d->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $d;
    }
}
