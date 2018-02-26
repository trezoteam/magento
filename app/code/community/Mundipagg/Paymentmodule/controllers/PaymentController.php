<?php

class Mundipagg_Paymentmodule_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function processPaymentAction()
    {
        $this->standard = Mage::getModel('paymentmodule/standard');
        $this->orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $this->order = $this->standard->getOrderByOrderId($this->orderId);

        $paymentMethod = $this->order->getPayment()->getMethodInstance()->getCode();

        // @todo find a better name
        $exploded = explode("_",$paymentMethod);

        $model = array_pop($exploded);
        $model = 'paymentmodule/paymentmethods_' . $model;

        $model = Mage::getModel($model);

        if ($model !== false) {
            $model->processPayment();
            return;
        }

        $this->_redirect('checkout/onepage/failure', ['_secure' => true]);
    }
}
