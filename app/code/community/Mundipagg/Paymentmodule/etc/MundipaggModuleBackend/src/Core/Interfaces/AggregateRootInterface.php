<?php

namespace MundipaggModuleBackend\Core\Interfaces;

interface AggregateRootInterface
{
    public function isDisabled();
    public function setDisabled($disabled);
    public function getId();
}