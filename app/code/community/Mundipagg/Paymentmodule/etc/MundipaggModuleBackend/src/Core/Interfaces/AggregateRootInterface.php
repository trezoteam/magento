<?php

namespace MundipaggModuleBackend\Core\Interfaces;

use JsonSerializable;

interface AggregateRootInterface extends JsonSerializable
{
    public function isDisabled();
    public function setDisabled($disabled);
    public function getId();
}