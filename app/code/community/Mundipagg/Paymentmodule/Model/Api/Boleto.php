<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateBoletoPaymentRequest;
use MundiAPILib\Models\CreateOrderItemRequest;

class Mundipagg_Paymentmodule_Model_Api_Boleto
{
    public function getCreateOrderRequest($paymentInformation)
    {
        $orderRequest = new CreateOrderRequest();

        $standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();

        $orderRequest->items = $this->getItems($paymentInformation->getItemsInfo());
        $orderRequest->customer = $this->getCustomerRequest($paymentInformation->getCustomerInfo());
        $orderRequest->payments = $this->getPayments($paymentInformation->getPaymentInfo());
        $orderRequest->code = $orderId;
        $orderRequest->metadata = $paymentInformation->getMetainfo();

        return $orderRequest;
    }

    private function getItems($itemsInfo)
    {
        $items = [];

        foreach ($itemsInfo as $item) {
            $orderItemRequest = new CreateOrderItemRequest();

            $orderItemRequest->amount = $item->getAmount();
            $orderItemRequest->quantity = $item->getQuantity();
            $orderItemRequest->description = $item->getDescription();

            $items[] = $orderItemRequest;
        }

        return $items;
    }

    private function getCustomerRequest($customerInfo)
    {
        $customerRequest = new CreateCustomerRequest();

        $customerRequest->name = $customerInfo->getName();
        $customerRequest->email = $customerInfo->getEmail();
        $customerRequest->document = $customerInfo->getDocument();
        $customerRequest->type = $customerInfo->getType();
        $customerRequest->address = $this->getCreateAddressRequest($customerInfo->getAddress());
        $customerRequest->phones = $this->getCreatePhonesRequest($customerInfo->getPhones());

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
        $addressRequest->complement = $addressInfo->getComplement();
        $addressRequest->country = $addressInfo->getCountry();

        return $addressRequest;
    }

    private function getCreatePhonesRequest($phonesInfo)
    {
        return new CreatePhonesRequest(
            $this->getHomePhone($phonesInfo),
            $this->getMobilePhone($phonesInfo)
        );
    }

    private function getHomePhone($phonesInfo)
    {
        return new CreatePhoneRequest(
            $phonesInfo->getCountryCode(),
            $phonesInfo->getNumber(),
            $phonesInfo->getAreacode()
        );
    }

    private function getMobilePhone($phonesInfo)
    {
        return new CreatePhoneRequest(
            $phonesInfo->getCountryCode(),
            $phonesInfo->getNumber(),
            $phonesInfo->getAreacode()
        );
    }

    private function getPayments($paymentInfo)
    {
        $paymentRequest = new CreatePaymentRequest();

        $boletoPaymentRequest = new CreateBoletoPaymentRequest();
        $boletoPaymentRequest->bank = $paymentInfo->getBank();
        $boletoPaymentRequest->instructions = $paymentInfo->getInstructions();
        $boletoPaymentRequest->dueAt = $paymentInfo->getDueAt();

        $paymentRequest->paymentMethod = 'boleto';
        $paymentRequest->boleto = $boletoPaymentRequest;
        // @todo this should not be hard coded
        $paymentRequest->currency = 'BRL';

        return [$paymentRequest];
    }
}
