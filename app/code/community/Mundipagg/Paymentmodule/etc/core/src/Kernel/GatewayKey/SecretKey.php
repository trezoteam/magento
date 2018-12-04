<?php

namespace Mundipagg\Core\Kernel\GatewayKey;

use Mundipagg\Exception\InvalidDataException;

final class SecretKey extends AbstractGatewayKey
{
    protected function setValue($value)
    {
        $key = strval($value);

        if (!preg_match('/sk_\w{16}$/',$key)) {
            throw new InvalidDataException('Invalid secret key passed!');
        }

        $this->value = $key;
    }
}