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
        $this->standard = Mage::getModel('paymentmodule/standard');
        $this->orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $this->order = $this->standard->getOrderByOrderId($this->orderId);
        $grandTotal = $this->order->getGrandTotal();

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
        $this->standard = Mage::getModel('paymentmodule/standard');
        $this->orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $this->order = $this->standard->getOrderByOrderId($this->orderId);
        $grandTotal = $this->order->getGrandTotal();

        return floatval($grandTotal);
    }

    public function getPublicKey()
    {
        $generalConfig = Mage::getModel('paymentmodule/config_general');
        return $generalConfig->getPublicKey();
    }
}

