<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateCreditCardPaymentRequest;

class Mundipagg_Paymentmodule_Model_Api_Creditcard extends Mundipagg_Paymentmodule_Model_Api_Standard
{
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

    protected function getCreatePhonesRequest($phonesInfo)
    {
        $phonesRequest = new CreatePhonesRequest();

        $phonesRequest->homePhone = $this->getHomePhone($phonesInfo);
        $phonesRequest->mobilePhone = $this->getMobilePhone($phonesInfo);

        return $phonesRequest;
    }

    protected function getHomePhone($phonesInfo)
    {
        $homePhoneRequest = new CreatePhoneRequest();

        $homePhoneRequest->countryCode = $phonesInfo->getCountryCode();
        $homePhoneRequest->number = $phonesInfo->getNumber();
        $homePhoneRequest->areaCode = $phonesInfo->getAreacode();

        return $homePhoneRequest;
    }

    protected function getMobilePhone($phonesInfo)
    {
        $mobilePhoneRequest = new CreatePhoneRequest();

        $mobilePhoneRequest->countryCode = $phonesInfo->getCountryCode();
        $mobilePhoneRequest->number = $phonesInfo->getNumber();
        $mobilePhoneRequest->areaCode = $phonesInfo->getAreacode();

        return $mobilePhoneRequest;
    }

    public function getPayments($paymentInfo)
    {
        $paymentRequest = new CreatePaymentRequest();

        $creditCardPaymentRequest = new CreateCreditCardPaymentRequest();
        $creditCardPaymentRequest->installments = $paymentInfo->getInstallmentNumber();
        $creditCardPaymentRequest->statementDescriptor = $paymentInfo->getInvoiceName();
        $creditCardPaymentRequest->cardToken = $paymentInfo->getPaymentToken();
        $creditCardPaymentRequest->capture = $paymentInfo->getOperationType();

        $paymentRequest->paymentMethod = 'credit_card';
        $paymentRequest->currency = $paymentInfo->getCurrency();
        $paymentRequest->creditCard = $creditCardPaymentRequest;
        /*
         * $paymentInfo->getBaseGrandTotal() and $paymentInfo->getInterest() returns arrays
         * for the future implementation of more than one creditcard payment methods.
         */
        $paymentRequest->amount = $paymentInfo->getBaseGrandTotal();
        $paymentRequest->amount = $paymentRequest->amount[0];
        //add interest
        $interest =  $paymentInfo->getInterest();
        $interest = $interest[0];
        $paymentRequest->amount += $interest;

        return array($paymentRequest);
    }
}