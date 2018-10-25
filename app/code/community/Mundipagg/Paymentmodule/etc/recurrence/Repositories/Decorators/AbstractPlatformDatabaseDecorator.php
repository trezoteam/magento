<?php
/**
 * @todo Move it to a global module context.
 * @todo Database access is not used only on recurrence context.
 */
namespace Mundipagg\Recurrence\Repositories\Decorators;

use Exception;

abstract class AbstractPlatformDatabaseDecorator
{
    protected $db;
    protected $tablePrefix;
    protected $tableArray;

    public function __construct($dbObject)
    {
        $this->db = $dbObject;
        $this->setTablePrefix();
        $this->setTableArray();
    }

    public function query($query)
    {
        $this->doQuery($query);
    }

    public function fetch($query)
    {
        $queryResult = $this->doFetch($query);
        return $this->formatResults($queryResult);
    }


    public function getTable($tableName)
    {
        if (isset($this->tableArray[$tableName])) {
            return $this->tableArray[$tableName];
        }
        throw new Exception("Table name '$tableName' not found!");
    }

    abstract public function getLastId();
    abstract protected function setTableArray();
    abstract protected function setTablePrefix();

    abstract protected function doQuery($query);
    abstract protected function doFetch($query);
    abstract protected function formatResults($query);
    abstract protected function setLastInsertId($insertId);
}