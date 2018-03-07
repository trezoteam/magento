<?php

class Mundipagg_Paymentmodule_Model_Config_Twocreditcards
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/twocreditcards_group/twocreditcards_status') == 1;
    }
}
