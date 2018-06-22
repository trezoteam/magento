<?php

class Mundipagg_Paymentmodule_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }

    public function processPaymentAction()
    {
        $this->standard = Mage::getModel('paymentmodule/standard');
        $this->orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $this->order = $this->standard->getOrderByOrderId($this->orderId);

        $paymentMethod = $this->order->getPayment()
            ->getMethodInstance()->getCode();

        // @todo find a better name
        $method = explode("_", $paymentMethod);

        $methodName = $method[1];
        $methodModel = 'paymentmodule/paymentmethods_standard';

        $model = Mage::getModel($methodModel);

        if ($model !== false) {
            return $model->processPayment($methodName);
        }

        $this->_redirect('checkout/onepage/failure', ['_secure' => true]);
    }
}
