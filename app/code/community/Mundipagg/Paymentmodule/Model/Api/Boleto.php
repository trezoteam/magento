<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateBoletoPaymentRequest;

class Mundipagg_Paymentmodule_Model_Api_Boleto extends Mundipagg_Paymentmodule_Model_Api_Standard
{
    public function getPayment($paymentInfo)
    {
        $boletoConfig = Mage::getModel('paymentmodule/config_boleto');
        $monetary = Mage::helper('paymentmodule/monetary');

        $bank = $boletoConfig->getBank();
        $instructions = $boletoConfig->getInstructions();
        $dueAt = $boletoConfig->getDueAt();

        $result = [];

        foreach ($paymentInfo as $payment) {
            $paymentRequest = new CreatePaymentRequest();

            $boletoPaymentRequest = new CreateBoletoPaymentRequest();

            $boletoPaymentRequest->bank = $bank;
            $boletoPaymentRequest->instructions = $instructions;
            $boletoPaymentRequest->dueAt = $dueAt;

            $paymentRequest->paymentMethod = 'boleto';
            $paymentRequest->boleto = $boletoPaymentRequest;
            $paymentRequest->amount = round($monetary->toCents($payment['value']));
            $paymentRequest->customer = $this->getCustomer($payment);
            $paymentRequest->currency = $this->getCurrentCurrencyCode();

            $result[] = $paymentRequest;
        }

        return $result;
    }

    /**
     * @param array $payment
     * @return CreateCustomerRequest
     */
    protected function getCustomer($payment)
    {
        if (
            isset($payment['multiBuyerEnabled']) &&
            $payment['multiBuyerEnabled'] === 'on'
        ) {
            return $this->getCustomerFromMultiBuyer($payment);
        }

        return $this->getCustomerFromSession($payment['taxvat']);
    }

    /**
     * @param string $documentNumber
     * @return CreateCustomerRequest
     */
    protected function getCustomerFromSession($documentNumber)
    {
        $customerRequest = new CreateCustomerRequest();
        $session = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();

        $customerRequest->name = $customer->getName();
        $customerRequest->document = $documentNumber;
        $customerRequest->address = $this->getAddressFromSession();
        $customerRequest->type = 'individual';
        $customerRequest->email = $customer->getEmail();

        return $customerRequest;
    }

    /**
     * @return CreateAddressRequest
     */
    protected function getAddressFromSession()
    {
        $session = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();
        $address = $customer->getPrimaryBillingAddress();
        $addressRequest = new CreateAddressRequest();

        $addressRequest->street = $address->getStreet()[0];
        $addressRequest->number = $address->getStreet()[1];
        $addressRequest->complement = $address->getStreet()[2];
        $addressRequest->neighborhood = $address->getStreet()[3];
        $addressRequest->city = $address->getCity();
        $addressRequest->state = $address->getRegion();
        $addressRequest->country = $address->getCountryId();
        $addressRequest->zipCode = $address->getPostcode();

        return $addressRequest;
    }

    /**
     * @param $customer
     * @return CreateCustomerRequest
     */
    protected function getCustomerFromMultiBuyer($customer)
    {
        $customerRequest = new CreateCustomerRequest();

        $customerRequest->name = $customer['multiBuyerName'];
        $customerRequest->document = $customer['multiBuyerTaxvat'];
        $customerRequest->email = $customer['multiBuyerEmail'];
        $customerRequest->address = $this->getAddressFromMultiBuyer($customer);
        $customerRequest->type = 'individual';

        return $customerRequest;
    }

    /**
     * @param array $customer
     * @return CreateAddressRequest
     */
    protected function getAddressFromMultiBuyer($customer)
    {
        $addressRequest = new CreateAddressRequest();

        $addressRequest->street = $customer['multiBuyerStreet'];
        $addressRequest->number = $customer['multiBuyerNumber'];
        $addressRequest->zipCode = $customer['multiBuyerZipCode'];
        $addressRequest->neighborhood = $customer['multiBuyerNeighborhood'];
        $addressRequest->city = $customer['multiBuyerCity'];
        $addressRequest->state = $customer['multiBuyerState'];
        $addressRequest->complement = $customer['multiBuyerComplement'];
        $addressRequest->country = $customer['multiBuyerCountry'];

        return $addressRequest;
    }
}
