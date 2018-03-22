<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateCreditCardPaymentRequest;

class Mundipagg_Paymentmodule_Model_Api_Creditcard extends Mundipagg_Paymentmodule_Model_Api_Standard
{
    public function getPayment($paymentInfo)
    {
        $monetary = Mage::helper('paymentmodule/monetary');

        $result = [];

        foreach ($paymentInfo as $payment) {
            $paymentRequest = new CreatePaymentRequest();

            $creditCardPaymentRequest = new CreateCreditCardPaymentRequest();

            $creditCardPaymentRequest->installments = $payment['creditCardInstallments'];
            $creditCardPaymentRequest->cardToken = $payment['token'] ?? '';

            if (
                $payment['SavedCreditCard'] &&
                $this->validateSavedCreditCard($payment['SavedCreditCard'])
            ) {
                $creditCardPaymentRequest->cardId = $payment['SavedCreditCard'];
            }

            $paymentRequest->paymentMethod = 'credit_card';
            $paymentRequest->creditCard = $creditCardPaymentRequest;
            $paymentRequest->amount = $monetary->toCents($payment['value']);
            $paymentRequest->customer = $this->getCustomer();
            // @todo this should not be hard coded
            $paymentRequest->currency = 'BRL';

            $result[] = $paymentRequest;
        }

        return $result;
    }

    private function getCustomer()
    {
        $session = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();
        $customerRequest = new CreateCustomerRequest();

        $customerRequest->name = $customer->getName();
        $customerRequest->address = $this->getAddress();
        $customerRequest->type = 'individual';
        $customerRequest->email = $customer->getEmail();

        return $customerRequest;
    }

    private function getAddress()
    {
        $session = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();
        $adress = $customer->getPrimaryBillingAddress();
        $addressRequest = new CreateAddressRequest();

        $addressRequest->street = $adress->getStreet()[0];
        $addressRequest->number = $adress->getStreet()[1];
        $addressRequest->zipCode = $adress->getPostcode();
        $addressRequest->neighborhood = 'Comptown';
        $addressRequest->city = $adress->getCity();;
        $addressRequest->state = $adress->getRegion();;
        $addressRequest->complement = '';
        $addressRequest->country = $adress->getCountryId();

        return $addressRequest;
    }

    private function validateSavedCreditCard($mundipaggCardId)
    {
        $session = Mage::getSingleton('customer/session');
        $model = Mage::getModel('paymentmodule/savedcreditcard');

        $customerId = $session->getCustomer()->getId();
        $card = $model->loadByMundipaggCardId($mundipaggCardId);

        if($card->getCustomerId() == $customerId) {
            return true;
        }

        return false;
    }
}
