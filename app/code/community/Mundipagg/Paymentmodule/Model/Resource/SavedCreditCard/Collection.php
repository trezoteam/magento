<?php
class Mundipagg_Paymentmodule_Model_Resource_SavedCreditCard_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('mundipagg_paymentmodule/saved_credit_card');
    }
}