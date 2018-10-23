<?php

class Mundipagg_Paymentmodule_Model_Recurrencetemplate extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('paymentmodule/recurrencetemplate');
    }

    public function loadById($id)
    {
        return $this->load($id, 'teste');
    }

    public function loadByCustomerId($customerId)
    {
        return $this->load($customerId, 'customer_id');
    }

}