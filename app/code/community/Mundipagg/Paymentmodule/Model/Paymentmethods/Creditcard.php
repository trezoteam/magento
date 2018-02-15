<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_Model_Paymentmethods_Creditcard extends Mundipagg_Paymentmodule_Model_Paymentmethods_Standard
{
    /**
     * Gather credit card card transaction information and try to create
     * a payment using the sdk api wrapper.
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
            $helperLog->error("Exception: " . $e->getMessage());
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
        $standard = Mage::getModel('paymentmodule/standard');
        $creditCardConfig = Mage::getModel('paymentmodule/config_card');
        $antifraudConfig = Mage::getModel('paymentmodule/config_antifraud');

        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();

        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId);
        $interest = $additionalInformation['mundipagg_payment_module_interest'];
        $baseGrandTotal = $additionalInformation['mundipagg_payment_module_base_grand_total'];

        $payment = new Varien_Object();

        // @todo get this from front end
        $payment->setInstallmentNumber($additionalInformation['mundipagg_payment_module_installments']);
        $payment->setPaymentMethod('credit_card');
        $payment->setInvoiceName($creditCardConfig->getInvoiceName());
        $payment->setOperationType($creditCardConfig->getOperationTypeFlag());
        $payment->setPaymentToken($additionalInformation['mundipagg_payment_module_token']);
        $payment->setHolderName($additionalInformation['mundipagg_payment_module_holder_name']);
        $payment->setBaseGrandTotal([$baseGrandTotal]);
        $payment->setInterest([$interest]);
        $payment->setCurrency('BRL'); // @todo get this from store config
        $payment->setSendToAntiFraud($antifraudConfig->shouldApplyAntifraud($baseGrandTotal));

        return $payment;
    }
}
