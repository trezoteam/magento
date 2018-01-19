<?php

class Mundipagg_Paymentmodule_Model_Config_Antifraud
{
    const basePath = 'mundipagg_config/antifraud_group/';

    public function isEnabled()
    {
        return Mage::getStoreConfig(self::basePath . 'antifraud_status') == 1;
    }

    public function getMinimumValue()
    {
        return Mage::getStoreConfig(self::basePath . 'antifraud_minimum');
    }

    public function shouldApplyAntifraud($amountInCents)
    {
        return $this->isEnabled() && $amountInCents >= ($this->getMinimumValue() * 100);
    }
}
