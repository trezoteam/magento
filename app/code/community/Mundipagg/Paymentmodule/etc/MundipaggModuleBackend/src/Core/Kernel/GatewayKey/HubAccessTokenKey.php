<?php

namespace MundipaggModuleBackend\Core\Kernel\GatewayKey;

use MundipaggModuleBackend\Exception\InvalidDataException;

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