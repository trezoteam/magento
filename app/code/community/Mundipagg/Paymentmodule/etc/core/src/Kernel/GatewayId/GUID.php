<?php

namespace Mundipagg\Core\Kernel\GatewayId;

use Mundipagg\Exception\InvalidDataException;

class GUID extends AbstractGatewayId
{
    protected function setValue($value)
    {
        $id = strval($value);

        if (!preg_match('/\w{8}-(\w{4}-){3}\w{12}$/',$id)) {
            throw new InvalidDataException('Invalid GUID passed!');
        }

        $this->value = $id;
        return $this;
    }
}