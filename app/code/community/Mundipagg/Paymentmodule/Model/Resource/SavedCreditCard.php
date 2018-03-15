<?php

class Mundipagg_Paymentmodule_Model_Resource_SavedCreditCard extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('mundipagg_paymentmodule/saved_credit_card', 'id');
    }
}