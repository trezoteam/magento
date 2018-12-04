<?php

namespace Mundipagg\Core\Kernel\GatewayId;

use Mundipagg\Exception\InvalidDataException;

class MerchantId extends AbstractGatewayId
{
    protected function setValue($value)
    {
        $id = strval($value);

        if (!preg_match('/merch_\w{16}$/',$id)) {
            throw new InvalidDataException('Invalid Merchant Id passed!');
        }

        $this->value = $id;
        return $this;
    }
}