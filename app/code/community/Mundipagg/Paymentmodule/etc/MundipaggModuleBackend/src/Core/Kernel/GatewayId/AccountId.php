<?php

namespace MundipaggModuleBackend\Core\Kernel\GatewayId;

use MundipaggModuleBackend\Exception\InvalidDataException;

final class AccountId extends AbstractGatewayId
{
    /**
     * @param string $value
     * @return $this
     * @throws InvalidDataException
     */
    protected function setValue($value)
    {
        $id = strval($value);

        if (!preg_match('/acc_\w{16}$/',$id)) {
            throw new InvalidDataException('Invalid Account Id passed!');
        }

        $this->value = $id;
        return $this;
    }
}