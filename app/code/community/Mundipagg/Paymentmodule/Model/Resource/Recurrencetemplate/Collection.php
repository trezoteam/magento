<?php
class Mundipagg_Paymentmodule_Model_Resource_Recurrencetemplate_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('paymentmodule/recurrencetemplate');
    }
}