<?php

namespace MartinusDev\ShipmentsTracking\HttpClient;

use JsonSerializable;

abstract class AbstractHttpClient implements JsonSerializable
{
    function __toString()
    {
        return self::class;
    }

    public function jsonSerialize()
    {
        return ['class' => self::class];
    }
}
