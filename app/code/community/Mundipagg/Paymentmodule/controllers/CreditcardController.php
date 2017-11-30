<?php

class Mundipagg_Paymentmodule_CreditcardController extends Mundipagg_Paymentmodule_Controller_Payment
{
    /**
     * Gather card transaction information and try to create
     * payment using sdk api wrapper.
     */
    public function processPaymentAction()
    {
        $order = Mage::getModel('paymentmodule/api_order');

        $creditCardModel = Mage::getModel('paymentmodule/creditcard');
//        $creditCardModel->getInfoInstance();

        $paymentInfo = new Varien_Object();

        $paymentInfo->setItemsInfo($this->getItemsInformation());
        $paymentInfo->setCustomerInfo($this->getCustomerInformation());
        $paymentInfo->setPaymentInfo($this->getPaymentInformation());
        $paymentInfo->setMetaInfo(Mage::helper('paymentmodule/data')->getMetaData());

        $result = $order->createCreditcardPayment($paymentInfo);
        $this->handleSuccessCreditCardTransaction($result);
    }

    /**
     * Take the result from processPaymentTransaction and redirect customer to
     * success page
     *
     * @param $resultTransaction
     */
    private function handleSuccessCreditCardTransaction($resultTransaction)
    {
//        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
    }

    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    private function getPaymentInformation()
    {
        $creditCardConfig = Mage::getModel('paymentmodule/config_card');

        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel("sales/order")->load($orderId);

        $payment = new Varien_Object();

        $payment->setPaymentMethod('credit_card');

        return $payment;
    }
}
