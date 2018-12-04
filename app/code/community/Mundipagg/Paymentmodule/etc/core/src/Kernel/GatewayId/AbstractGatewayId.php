<?php

namespace Mundipagg\Core\Kernel\GatewayId;

use Mundipagg\Core\Interfaces\ValueObjectInterface;

abstract class AbstractGatewayId implements ValueObjectInterface
{
    /** @var string */
    protected $value;

    public function __construct($value)
    {
        $id = strval($value);
        if (strlen($id)) {
            $this->setValue($id);
        }
    }

    /**
     * @return string;
     */
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
     * @param string $value
     * @return $this
     * @throws InvalidDataException
     */
    abstract protected function setValue($value);
}