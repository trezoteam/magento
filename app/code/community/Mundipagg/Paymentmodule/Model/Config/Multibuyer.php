<?php

class Mundipagg_Paymentmodule_Model_Config_Multibuyer
{
    const basePath = 'mundipagg_config/multibuyer_group/';

    public function isEnabled()
    {
        return Mage::getStoreConfig(self::basePath . 'multibuyer_status') == 1;
    }
}
