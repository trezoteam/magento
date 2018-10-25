<?php

namespace Mundipagg\Recurrence\Repositories\Decorators;

use Mage;

class MagentoPlatformDatabaseDecorator extends AbstractPlatformDatabaseDecorator
{
    protected function setTableArray()
    {
        $this->tableArray = [
            "TEMPLATE_TABLE" =>  $this->table_prefix . "paymentmodule_recurrencetemplate",
            "TEMPLATE_REPETITION_TABLE" =>  $this->table_prefix . "paymentmodule_recurrencetemplaterepetition",
            "RECURRENCY_PRODUCT_TABLE" => $this->table_prefix . "paymentmodule_recurrenceproduct",
            "RECURRENCY_SUBPRODUCT_TABLE" => $this->table_prefix . "paymentmodule_recurrencesubproduct",
        ];
    }

    protected function doQuery($query)
    {
        $connection = $this->db->getConnection('core_write');
        $connection->query($query);
        $this->setLastInsertId($connection->lastInsertId());
    }

    protected function formatResults($queryResult)
    {
        return $queryResult;
    }

    protected function doFetch($query)
    {
        $connection = $this->db->getConnection('core_read');
        return $connection->fetchAll($query);
    }

    public function getLastId()
    {
        return $this->db->lastInsertId;
    }

    protected function setTablePrefix()
    {
        $this->tablePrefix = Mage::getConfig()->getTablePrefix();
    }

    protected function setLastInsertId($lastInsertId)
    {
        $this->db->lastInsertId = $lastInsertId;
    }
}