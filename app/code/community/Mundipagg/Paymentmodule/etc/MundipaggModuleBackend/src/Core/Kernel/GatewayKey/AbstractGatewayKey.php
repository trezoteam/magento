<?php

namespace MundipaggModuleBackend\Core\Kernel\GatewayKey;

use JsonSerializable;
use MundipaggModuleBackend\Core\Interfaces\ValueObjectInterface;

abstract class AbstractGatewayKey implements ValueObjectInterface, JsonSerializable
{
    protected $value;

    public function __construct($value)
    {
        $key = strval($value);
        if (strlen($key)) {
            $this->setValue($value);
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    /** @var static $object */
    public function equals($object)
    {
        return
            $this->value === $object->getValue() &&
            static::class === get_class($object);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return AbstractGatewayKey
     */
    protected abstract function setValue($value);
}