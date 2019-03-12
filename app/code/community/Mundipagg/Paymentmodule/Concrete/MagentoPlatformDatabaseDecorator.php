<?php

namespace Mundipagg\Magento\Concrete;

use Mage;
use Mundipagg\Core\Kernel\Abstractions\AbstractDatabaseDecorator;

final class MagentoPlatformDatabaseDecorator extends AbstractDatabaseDecorator
{
    protected function setTableArray()
    {
        $this->tableArray = [
            AbstractDatabaseDecorator::TABLE_MODULE_CONFIGURATION =>
                $this->table_prefix . "paymentmodule_configuration",

            AbstractDatabaseDecorator::TABLE_HUB_INSTALL_TOKEN =>
                $this->table_prefix . "paymentmodule_hub_install_token",

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
        $retn = new \StdClass;
        $retn->num_rows = count($queryResult);
        $retn->row = array();
        if (!empty($queryResult)) {
            $retn->row = $queryResult[0];
        }
        $retn->rows = $queryResult;
        return $retn;
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