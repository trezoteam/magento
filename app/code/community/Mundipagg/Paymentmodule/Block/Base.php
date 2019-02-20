<?php

class Mundipagg_Paymentmodule_Block_Base extends Mage_Payment_Block_Form
{
    public function __construct()
    {
        Mundipagg_Paymentmodule_Model_MagentoModuleCoreSetup::bootstrap();
        parent::__construct();
    }

    public function getCurrentCurrencySymbol()
    {
        return Mage::helper('paymentmodule/monetary')->getCurrentCurrencySymbol();
    }
}