<?php

namespace MundipaggModuleBackend\Core\Kernel\GatewayKey;

use MundipaggModuleBackend\Exception\InvalidDataException;

final class TestSecretKey extends AbstractGatewayKey
{
    protected function setValue($value)
    {
        $key = strval($value);

        if (!preg_match('/sk_test_\w{16}$/',$key)) {
            throw new InvalidDataException('Invalid test secret key passed!');
        }

        $this->value = $key;
    }
}