<?php

use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreateShippingRequest;

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
        $orderRequest->shipping = $this->getShippingRequest($paymentInformation->getShippingInfo());

        return $orderRequest;
    }

    protected function getShippingRequest($shippingInformation) {
        $shippingRequest = new CreateShippingRequest();

        $shippingRequest->amount = $shippingInformation->getAmount();
        $shippingRequest->description = $shippingInformation->getDescription();
        //$shippingRequest->recipientName = ""; @todo
        //$shippingRequest->recipientPhone = ""; @todo
        //$shippingRequest->addressId = null; @todo
        $shippingRequest->address = $this->getCreateAddressRequest($shippingInformation->getAddress());

        return $shippingRequest;
    }

    protected function getCreateAddressRequest($addressInfo)
    {
        $addressRequest = new CreateAddressRequest();

        $addressRequest->street = $addressInfo->getStreet();
        $addressRequest->number = $addressInfo->getNumber();
        $addressRequest->zipCode = $addressInfo->getZipCode();
        $addressRequest->neighborhood = $addressInfo->getNeighborhood();
        $addressRequest->city = $addressInfo->getCity();
        $addressRequest->state = $addressInfo->getState();
        $addressRequest->complement = $addressInfo->getComplement();
        $addressRequest->country = $addressInfo->getCountry();
        $addressRequest->metadata = $addressInfo->getMetadata();

        return $addressRequest;
    }

}