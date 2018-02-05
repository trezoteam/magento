<?php

class Mundipagg_Paymentmodule_Block_Form_Boletocc extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/form/boletocc.phtml');
    }

    public function getGrandTotal()
    {
        $grandTotal = Mage::getModel('checkout/session')->getQuote()->getGrandTotal();
        return number_format($grandTotal, "2", ",", "");
    }

    public function getCurrencySymbol()
    {
        $currencySymbol = Mage::app()
            ->getLocale()
            ->currency(Mage::app()->getStore()->getCurrentCurrencyCode())
            ->getSymbol();
        return $currencySymbol;
    }

    public function getFloatGrandTotal()
    {
        $grandTotal = Mage::getModel('checkout/session')->getQuote()->getGrandTotal();
        return $grandTotal;
    }
}