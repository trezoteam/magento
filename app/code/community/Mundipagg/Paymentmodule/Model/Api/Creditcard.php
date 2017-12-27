<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateCreditCardPaymentRequest;


class Mundipagg_Paymentmodule_Model_Api_Creditcard
{
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

    private function getCustomerRequest($customerInfo)
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

    private function getCreateAddressRequest($addressInfo)
    {
        $addressRequest = new CreateAddressRequest();

        $addressRequest->street = $addressInfo->getStreet();
        $addressRequest->number = $addressInfo->getNumber();
        $addressRequest->zipCode = $addressInfo->getZipCode();
        $addressRequest->neighborhood = $addressInfo->getNeighborhood();
        $addressRequest->city = $addressInfo->getCity();
        $addressRequest->state = $addressInfo->getState();
        $addressRequest->country = $addressInfo->getCountry();
        $addressRequest->complement = $addressInfo->getComplement();
        $addressRequest->metadata = $addressInfo->getMetadata();

        return $addressRequest;
    }

    private function getCreatePhonesRequest($phonesInfo)
    {
        $phonesRequest = new CreatePhonesRequest();

        $phonesRequest->homePhone = $this->getHomePhone($phonesInfo);
        $phonesRequest->mobilePhone = $this->getMobilePhone($phonesInfo);

        return $phonesRequest;
    }

    private function getHomePhone($phonesInfo)
    {
        $homePhoneRequest = new CreatePhoneRequest();

        $homePhoneRequest->countryCode = $phonesInfo->getCountryCode();
        $homePhoneRequest->number = $phonesInfo->getNumber();
        $homePhoneRequest->areaCode = $phonesInfo->getAreacode();

        return $homePhoneRequest;
    }

    private function getMobilePhone($phonesInfo)
    {
        $mobilePhoneRequest = new CreatePhoneRequest();

        $mobilePhoneRequest->countryCode = $phonesInfo->getCountryCode();
        $mobilePhoneRequest->number = $phonesInfo->getNumber();
        $mobilePhoneRequest->areaCode = $phonesInfo->getAreacode();

        return $mobilePhoneRequest;
    }

    private function getPayments($paymentInfo)
    {
        $paymentRequest = new CreatePaymentRequest();

        $creditCardPaymentRequest = new CreateCreditCardPaymentRequest();
        $creditCardPaymentRequest->installments = $paymentInfo->getInstallmentNumber();
        $creditCardPaymentRequest->statementDescriptor = $paymentInfo->getInvoiceName();
        $creditCardPaymentRequest->cardToken = $paymentInfo->getPaymentToken();
        $creditCardPaymentRequest->capture = $paymentInfo->getOperationType();

        $paymentRequest->paymentMethod = $paymentInfo->getPaymentMethod();
        $paymentRequest->currency = $paymentInfo->getCurrency();
        $paymentRequest->creditCard = $creditCardPaymentRequest;

        return array($paymentRequest);
    }
}