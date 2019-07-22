<?php

class Mundipagg_Paymentmodule_Block_Checkout_Information extends Mundipagg_Paymentmodule_Block_Base
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/checkout/information.phtml');

        $this->initAdditionalInformation();
    }

    protected function initAdditionalInformation()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $payment = Mage::helper('paymentmodule/order')->getOrderPayment($orderId);
        $additionalInformation = $payment->getAdditionalInformation();

        if (isset($additionalInformation['mundipagg_payment_method'])) {
            $paymentMethod = $additionalInformation['mundipagg_payment_method'];
            $paymentInfo = $additionalInformation[$paymentMethod];

            $this->setPaymentInformation($paymentInfo);
        }
    }

    public function getBilletData()
    {
        $paymentInformation = $this->getPaymentInformation();

        $billetData = array();
        if (isset($paymentInformation['boleto'])) {
            $billetData = $paymentInformation['boleto'];
        }

        return $billetData;
    }
}
