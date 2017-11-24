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

        $result = $order->createBoletoPayment($paymentInfo);
        $this->handleSuccessBoletoTransaction($result);
    }

    /**
     * Take the result from processPaymentTransaction and redirect customer to
     * success page
     *
     * @param $resultTransaction
     */
    private function handleSuccessBoletoTransaction($resultTransaction)
    {
        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
    }

    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    private function getPaymentInformation()
    {
        $boletoConfig = Mage::getModel('paymentmodule/config_boleto');

        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel("sales/order")->load($orderId);
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
