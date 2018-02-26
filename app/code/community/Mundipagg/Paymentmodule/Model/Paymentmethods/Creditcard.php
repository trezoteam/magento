<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_Model_Paymentmethods_Creditcard extends Mundipagg_Paymentmodule_Model_Paymentmethods_Standard
{
    /**
     * Gather information about payment
     *
     * @return Varien_Object
     * @throws Varien_Exception
     */
    protected function getPaymentInformation()
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
