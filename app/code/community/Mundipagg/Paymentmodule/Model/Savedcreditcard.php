<?php

class Mundipagg_Paymentmodule_Model_Savedcreditcard extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('paymentmodule/savedcreditcard');
    }

    public function loadMundipaggCardId($cardId)
    {
        return $this->load($cardId, 'mundipagg_card_id');
    }

}