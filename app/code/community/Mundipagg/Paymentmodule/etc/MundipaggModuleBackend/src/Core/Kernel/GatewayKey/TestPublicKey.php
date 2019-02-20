<?php

namespace MundipaggModuleBackend\Core\Kernel\GatewayKey;

use MundipaggModuleBackend\Exception\InvalidDataException;

final class TestPublicKey extends AbstractGatewayKey
{
    protected function setValue($value)
    {
        $key = strval($value);

        if (!preg_match('/pk_test_\w{16}$/',$key)) {
            throw new InvalidDataException('Invalid test public key passed!');
        }

        $this->value = $key;
    }
}