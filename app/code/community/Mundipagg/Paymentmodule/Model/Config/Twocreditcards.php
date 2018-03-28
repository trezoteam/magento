<?php

class Mundipagg_Paymentmodule_Model_Config_Twocreditcards
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/twocreditcards_group/twocreditcards_status') == 1;
    }

    public function getPaymentTitle()
    {
        return Mage::getStoreConfig('mundipagg_config/twocreditcards_group/twocreditcards_payment_title');
    }
}
