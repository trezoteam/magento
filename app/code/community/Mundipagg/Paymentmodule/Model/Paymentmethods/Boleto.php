<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_Model_Paymentmethods_Boleto extends Mundipagg_Paymentmodule_Model_Paymentmethods_Standard
{
    /**
     * Gather boleto transaction information and try to create
     * payment using sdk api wrapper.
     */
    public function processPayment()
    {
        $apiOrder = Mage::getModel('paymentmodule/api_order');

        $paymentInfo = new Varien_Object();

        $paymentInfo->setItemsInfo($this->getItemsInformation());
        $paymentInfo->setCustomerInfo($this->getCustomerInformation());
        $paymentInfo->setPaymentInfo($this->getPaymentInformation());
        $paymentInfo->setShippingInfo($this->getShippingInformation());
        $paymentInfo->setMetaInfo(Mage::helper('paymentmodule/data')->getMetaData());

        try {
            $response = $apiOrder->createPayment($paymentInfo);

            if (gettype($response) !== 'object' || get_class($response) != GetOrderResponse::class) {
                throw new Exception("Response must be object.");
            }
        } catch(Exception $e) {
            $helperLog = Mage::helper('paymentmodule/log');
            $orderId = $this->lastRealOrderId;
            $helperLog->error("Exception on $orderId: " . $e->getMessage());
            $helperLog->error(json_encode($response,JSON_PRETTY_PRINT));

            $response = new \stdClass();
            $response->status = 'failed';
        }

        $this->handleOrderResponse($response, true);
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
