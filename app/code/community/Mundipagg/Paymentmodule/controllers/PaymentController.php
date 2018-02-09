<?php

class Mundipagg_Paymentmodule_PaymentController extends Mundipagg_Paymentmodule_Controller_Payment
{
    public function processPaymentAction()
    {
        $this->standard = Mage::getModel('paymentmodule/standard');
        $this->orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $this->order = $this->standard->getOrderByOrderId($this->orderId);

        $paymentMethod = $this->order->getPayment()->getMethodInstance()->getCode();
        $controller = end(explode("_",$paymentMethod));
        $controller = 'paymentmodule/paymentmethods_' . $controller;
        $controller = Mage::getModel($controller);
        $controller->processPayment();
    }
}
