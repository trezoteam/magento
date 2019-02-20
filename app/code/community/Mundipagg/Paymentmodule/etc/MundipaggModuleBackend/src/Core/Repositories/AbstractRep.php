<?php

namespace MundipaggModuleBackend\Core\Repositories;

use MundipaggModuleBackend\Core\Interfaces\AggregateRootInterface;
use MundipaggModuleBackend\Core\AbstractMundipaggModuleCoreSetup as MPSetup;

abstract class AbstractRep
{
    /** @var AbstractPlatformDatabaseDecorator */
    protected $db;

    /**
     * AbstractRep constructor.
     */
    public function __construct()
    {
       $this->db = MPSetup::getDatabaseAccessDecorator();
    }

    /** @todo it must handle mass saving */
    public function save(AggregateRootInterface &$object){
        $objectId = null;
        if (
            is_object($object) &&
            method_exists($object, 'getId')
        ) {
            $objectId = $object->getId();
        }
        if ($objectId === null) {
            return $this->create($object);
        }

        return $this->update($object);
    }

    abstract protected function create(AggregateRootInterface &$object);
    abstract protected function update(AggregateRootInterface &$object);
    abstract public function delete(AggregateRootInterface $object);
    abstract public function find($objectId);
    abstract public function listEntities($limit, $listDisabled);
    abstract public function findOrNew($objectId);
}