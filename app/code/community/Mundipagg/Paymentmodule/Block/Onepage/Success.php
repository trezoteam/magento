<?php

class Mundipagg_Paymentmodule_Block_Onepage_Success extends Mage_Core_Block_Template
{
    public function getBilletPrintUrl()
    {
        $order = Mage::getSingleton('checkout/session')->getLastRealOrder();
        $payment = Mage::helper('paymentmodule/order')->getOrderPayment($order->getId());
        $paymentInformation = $payment->getAdditionalInformation($payment->getMethod());
        $billetData = array();

        if (isset($paymentInformation['boleto'])) {
            $billetData = reset($paymentInformation['boleto']);
        }

        return $billetData;
    }
}