<?php

class Mundipagg_Paymentmodule_Helper_Monetary extends Mage_Core_Helper_Abstract
{
    public function toCents($amount){
        return floatval($amount) * 100;
    }

    public function toCurrencyFormat($amount)
    {
        return Mage::helper('core')->currency($amount, true, false);
    }
}