<?php

namespace MundipaggModuleBackend\Core\Interfaces;

interface ValueObjectInterface
{
    /** @var static $object */
    public function equals($object);
}