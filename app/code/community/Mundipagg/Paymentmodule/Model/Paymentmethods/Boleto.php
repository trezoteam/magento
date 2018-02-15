<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_Model_Paymentmethods_Boleto extends Mundipagg_Paymentmodule_Model_Paymentmethods_Standard
{
    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    protected function getPaymentInformation()
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
