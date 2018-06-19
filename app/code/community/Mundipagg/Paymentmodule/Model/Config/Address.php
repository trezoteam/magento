<?php

class Mundipagg_Paymentmodule_Model_Config_Address
{
    public function getStreet()
    {
        return Mage::getStoreConfig('mundipagg_config/address_group/street');
    }

    public function getNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/address_group/number');
    }

    public function getComplement()
    {
        return Mage::getStoreConfig('mundipagg_config/address_group/complement');
    }

    public function getNeighborhood()
    {
        return Mage::getStoreConfig('mundipagg_config/address_group/neighborhood');
    }
}
