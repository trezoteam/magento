<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_Model_Paymentmethods_Boletocc extends Mundipagg_Paymentmodule_Model_Paymentmethods_Standard
{
    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    protected function getPaymentInformation()
    {
        $this->initPaymentInfoSources();

        $payment = new Varien_Object();
        $this->getBoletoPaymentInformation($payment);
        $this->getCreditcardPaymentInformation($payment);

        return $payment;
    }

    private function initPaymentInfoSources() {
        $this->standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $this->standard->getCheckoutSession();
        $this->orderId = $checkoutSession->getLastRealOrderId();
        $this->lastRealOrderId = $this->orderId;
        $this->additionalInformation = $this->standard->getAdditionalInformationForOrder($this->orderId);
        $this->boletoCcConfig = Mage::getModel('paymentmodule/config_boletocc');

        $this->orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $this->order = $this->standard->getOrderByOrderId($this->orderId);
        $this->antifraudConfig = Mage::getModel('paymentmodule/config_antifraud');
    }

    private function getBoletoPaymentInformation(Varien_Object &$payment)
    {
        $grandTotal = $this->order->getGrandTotal();

        $payment->setPaymentMethod('boletocc');
        $payment->setAmount($grandTotal);
        $payment->setBank($this->boletoCcConfig->getBank());
        $payment->setInstructions($this->boletoCcConfig->getInstructions());
        $payment->setDueAt($this->boletoCcConfig->getDueAt());
        $payment->setBoletoValue($this->additionalInformation['mundipagg_payment_module_boleto_value']);
    }

    private function getCreditcardPaymentInformation(Varien_Object &$payment)
    {
        $interest = $this->additionalInformation['mundipagg_payment_module_interest'];
        $baseGrandTotal = $this->additionalInformation['mundipagg_payment_module_base_grand_total'];

        $payment->setInstallmentNumber($this->additionalInformation['mundipagg_payment_module_installments']);
        $payment->setInvoiceName($this->boletoCcConfig->getInvoiceName());
        $payment->setOperationType($this->boletoCcConfig->getOperationTypeFlag());
        $payment->setPaymentToken($this->additionalInformation['mundipagg_payment_module_token']);
        $payment->setHolderName($this->additionalInformation['mundipagg_payment_module_holder_name']);
        $payment->setBaseGrandTotal([$baseGrandTotal]);
        $payment->setInterest([$interest]);
        $payment->setCurrency('BRL'); // @todo get this from store config
        $payment->setSendToAntiFraud($this->antifraudConfig->shouldApplyAntifraud($baseGrandTotal));
        $payment->setCreditcardValue($this->additionalInformation['mundipagg_payment_module_creditcard_value']);
    }
}
