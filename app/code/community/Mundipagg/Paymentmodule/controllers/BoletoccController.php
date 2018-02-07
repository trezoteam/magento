<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_BoletoccController extends Mundipagg_Paymentmodule_Controller_Payment
{
    /**
     * Gather boleto transaction information and try to create
     * payment using sdk api wrapper.
     */
    public function processPaymentAction()
    {
        $apiOrder = Mage::getModel('paymentmodule/api_order');

        $paymentInfo = new Varien_Object();

        $paymentInfo->setItemsInfo($this->getItemsInformation());
        $paymentInfo->setCustomerInfo($this->getCustomerInformation());
        $paymentInfo->setPaymentInfo($this->getPaymentInformation());
        $paymentInfo->setShippingInfo($this->getShippingInformation());
        $paymentInfo->setMetaInfo(Mage::helper('paymentmodule/data')->getMetaData());

        try {
            $response = $apiOrder->createBoletoCreditcardPayment($paymentInfo);

            if (gettype($response) !== 'object' || get_class($response) != GetOrderResponse::class) {
                throw new Exception("Response must be object.");
            }

            $this->handleOrderResponse($response, true);
        } catch(Exception $e) {
            $helperLog = Mage::helper('paymentmodule/log');
            $helperLog->error("Exception: " . $e->getMessage());
            $helperLog->error(json_encode($response,JSON_PRETTY_PRINT));
        }
    }

    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    private function getPaymentInformation()
    {
        /** Boleto Payment */
        $boletoCcConfig = Mage::getModel('paymentmodule/config_boletocc');
        $standard = Mage::getModel('paymentmodule/standard');

        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);
        $grandTotal = $order->getGrandTotal();

        $payment = new Varien_Object();

        $payment->setPaymentMethod('boletocc');
        $payment->setAmount($grandTotal);
        $payment->setBank($boletoCcConfig->getBank());
        $payment->setInstructions($boletoCcConfig->getInstructions());
        $payment->setDueAt($boletoCcConfig->getDueAt());

        /** CreditCard Payment */
        $antifraudConfig = Mage::getModel('paymentmodule/config_antifraud');

        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();

        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId);
        $interest = $additionalInformation['mundipagg_payment_module_interest'];
        $baseGrandTotal = $additionalInformation['mundipagg_payment_module_base_grand_total'];

        // @todo get this from front end
        $payment->setInstallmentNumber($additionalInformation['mundipagg_payment_module_installments']);
        $payment->setInvoiceName($boletoCcConfig->getInvoiceName());
        $payment->setOperationType($boletoCcConfig->getOperationTypeFlag());
        $payment->setPaymentToken($additionalInformation['mundipagg_payment_module_token']);
        $payment->setHolderName($additionalInformation['mundipagg_payment_module_holder_name']);
        $payment->setBaseGrandTotal([$baseGrandTotal]);
        $payment->setInterest([$interest]);
        $payment->setCurrency('BRL'); // @todo get this from store config
        $payment->setSendToAntiFraud($antifraudConfig->shouldApplyAntifraud($baseGrandTotal));

        return $payment;
    }
}
