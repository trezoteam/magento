<?php

namespace Mundipagg\Core\Kernel\GatewayKey;

use Mundipagg\Exception\InvalidDataException;

final class PublicKey extends AbstractGatewayKey
{
    protected function setValue($value)
    {
        $key = strval($value);

        if (!preg_match('/pk_\w{16}$/',$key)) {
            throw new InvalidDataException('Invalid public key passed!');
        }

        $this->value = $key;
    }
}