<?php

namespace Mundipagg\Core\Kernel\GatewayKey;

use Mundipagg\Exception\InvalidDataException;

class HubAccessTokenKey extends AbstractGatewayKey
{
    protected function setValue($value)
    {
        $key = strval($value);

        if (!preg_match('/\w{64}$/',$key)) {
            throw new InvalidDataException('Invalid Hub Access Token passed!');
        }

        $this->value = $key;
    }
}