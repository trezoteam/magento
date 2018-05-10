<?php

use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreateShippingRequest;

abstract class Mundipagg_Paymentmodule_Model_Api_Standard
{
    protected function getCurrentCurrencyCode()
    {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    public function getCreateOrderRequest($paymentInformation)
    {
        $orderRequest = new CreateOrderRequest();

        $standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();

        $orderRequest->currency = $this->getCurrentCurrencyCode();
        $orderRequest->items = $paymentInformation->getItemsInfo();
        $orderRequest->customer = $this->getCustomerRequest($paymentInformation->getCustomerInfo());
        $orderRequest->payments = $this->getPayments();
        $orderRequest->code = $orderId;
        $orderRequest->metadata = $paymentInformation->getMetainfo();
        $orderRequest->shipping = $this->getShippingRequest($paymentInformation->getShippingInfo());
        $orderRequest->antifraudEnabled = $paymentInformation->getSendToAntiFraud();

        return $orderRequest;
    }

    protected function getPayments()
    {
        $standard = Mage::getModel('paymentmodule/standard');

        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();
        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId);

        $paymentMethod = $additionalInformation['mundipagg_payment_method'];
        $paymentInformation = $additionalInformation[$paymentMethod];

        $result = [];

        foreach ($paymentInformation as $key => $value) {
            $paymentApi = Mage::getModel('paymentmodule/api_' . $key);
            $result = array_merge($result, $paymentApi->getPayment($value));
        }

        return $result;
    }

    protected function getCustomerRequest($customerInfo)
    {
        $customerRequest = new CreateCustomerRequest();

        $customerRequest->name = $customerInfo->getName();
        $customerRequest->document = $customerInfo->getDocument();
        $customerRequest->email = $customerInfo->getEmail();
        $customerRequest->type = $customerInfo->getType();
        $customerRequest->address = $this->getCreateAddressRequest($customerInfo->getAddress());
        $customerRequest->phones = $this->getCreatePhonesRequest($customerInfo->getPhones());
        $customerRequest->code = $customerInfo->getCode();
        $customerRequest->metadata = $customerInfo->getMetadata();

        return $customerRequest;
    }

    protected function getShippingRequest($shippingInformation) {
        $shippingRequest = new CreateShippingRequest();

        $shippingRequest->amount = round($shippingInformation->getAmount());
        $shippingRequest->description = $shippingInformation->getDescription();
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

    protected function getCreatePhonesRequest($phonesInfo)
    {
        return new CreatePhonesRequest(
            $this->getHomePhone($phonesInfo),
            $this->getMobilePhone($phonesInfo)
        );
    }

    protected function getHomePhone($phonesInfo)
    {
        return new CreatePhoneRequest(
            $phonesInfo->getCountryCode(),
            $phonesInfo->getNumber(),
            $phonesInfo->getAreacode()
        );
    }

    protected function getMobilePhone($phonesInfo)
    {
        return new CreatePhoneRequest(
            $phonesInfo->getCountryCode(),
            $phonesInfo->getNumber(),
            $phonesInfo->getAreacode()
        );
    }
}
