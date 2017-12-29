<?php

use MundiAPILib\Models\CreateOrderRequest;

abstract class Mundipagg_Paymentmodule_Model_Api_Standard {

    public function getCreateOrderRequest($paymentInformation)
    {
        $orderRequest = new CreateOrderRequest();

        $standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();

        $orderRequest->items = $paymentInformation->getItemsInfo();
        $orderRequest->customer = $this->getCustomerRequest($paymentInformation->getCustomerInfo());
        $orderRequest->payments = $this->getPayments($paymentInformation->getPaymentInfo());
        $orderRequest->code = $orderId;
        $orderRequest->metadata = $paymentInformation->getMetainfo();

        return $orderRequest;
    }

}