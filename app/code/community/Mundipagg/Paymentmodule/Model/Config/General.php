<?php

class Mundipagg_Paymentmodule_Model_Config_General
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/module_status') == 1;
    }

    public function isLogEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/logs') == 1;
    }

    private function getProdSecretKey()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/sk_prod');
    }

    private function getTestSecretKey()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/sk_test');
    }

    private function getProdPublicKey()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/pk_prod');
    }

    private function getTestPublicKey()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/pk_test');
    }

    public function isTestModeEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/general_group/test_mode') == 1;
    }

    public function getSecretKey()
    {
        if ($this->isTestModeEnabled()) {
            return $this->getTestSecretKey();
        }

        return $this->getProdSecretKey();
    }

    public function getPublicKey()
    {
        if ($this->isTestModeEnabled()) {
            return $this->getTestPublicKey();
        }

        return $this->getProdPublicKey();
    }

    public function getPassword()
    {
        return '';
    }
}
