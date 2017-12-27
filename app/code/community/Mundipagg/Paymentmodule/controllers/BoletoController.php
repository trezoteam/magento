<?php

class Mundipagg_Paymentmodule_BoletoController extends Mundipagg_Paymentmodule_Controller_Payment
{
    /**
     * Gather boleto transaction information and try to create
     * payment using sdk api wrapper.
     */
    public function processPaymentAction()
    {
        $order = Mage::getModel('paymentmodule/api_order');

        $paymentInfo = new Varien_Object();

        $paymentInfo->setItemsInfo($this->getItemsInformation());
        $paymentInfo->setCustomerInfo($this->getCustomerInformation());
        $paymentInfo->setPaymentInfo($this->getPaymentInformation());
        $paymentInfo->setMetaInfo(Mage::helper('paymentmodule/data')->getMetaData());

        $response = $order->createBoletoPayment($paymentInfo);
        $this->handleOrderResponse($response,true);
    }

    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    private function getPaymentInformation()
    {
        $boletoConfig = Mage::getModel('paymentmodule/config_boleto');
        $standard = Mage::getModel('paymentmodule/standard');

        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);
        $grandTotal = $order->getGrandTotal();

        $payment = new Varien_Object();

        $payment->setPaymentMethod('boleto');
        $payment->setAmount($grandTotal);
        $payment->setBank($boletoConfig->getBank());
        $payment->setInstructions($boletoConfig->getInstructions());
        $payment->setDueAt($boletoConfig->getDueAt());

        return $payment;
    }
}
