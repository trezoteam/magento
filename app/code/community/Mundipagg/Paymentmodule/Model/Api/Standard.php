<?php

use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreateShippingRequest;

use Mundipagg_Paymentmodule_Helper_Address as AddressHelper;

abstract class Mundipagg_Paymentmodule_Model_Api_Standard
{
    const SESSION_ID = 'mp_session_id';

    protected function getMageSessionId()
    {
        $session = Mage::getSingleton('customer/session');

        $sessionId = $session->getData(self::SESSION_ID);

        if (empty($sessionId)) {
            $sessionId = uniqid('mpm1-');
            $session->setData(self::SESSION_ID, $sessionId);
        }

        return $sessionId;
    }

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
        $orderRequest->metadata = $paymentInformation->getMetaInfo();
        $orderRequest->shipping = $this->getShippingRequest($paymentInformation->getShippingInfo());
        $orderRequest->antifraudEnabled = $this->shouldSendToAntiFraud($paymentInformation);
        $orderRequest->sessionId = $this->getMageSessionId();

        return $orderRequest;
    }

    protected function shouldSendToAntiFraud($paymentInformation)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);

        $antiFraudConfig = Mage::getModel('paymentmodule/config_antifraud');
        $moneyHelper = Mage::helper('paymentmodule/monetary');

        $grandTotal = $moneyHelper->formatDecimals($order->getGrandTotal());
        $grandTotalInCents = $moneyHelper->toCents($grandTotal);

        return $antiFraudConfig->shouldApplyAntifraud($grandTotalInCents);
    }

    protected function getPayments()
    {
        $standard = Mage::getModel('paymentmodule/standard');

        $checkoutSession = $standard->getCheckoutSession();
        /** @var Mage_Sales_Model_Order $order */
        $order = $checkoutSession->getLastRealOrder();
        $payment = Mage::helper('paymentmodule/order')->getOrderPayment($order->getId());
        $billingAddress = $order->getBillingAddress();
        $additionalInformation = $payment->getAdditionalInformation();

        $paymentMethod = $additionalInformation['mundipagg_payment_method'];
        $paymentInformation = $additionalInformation[$paymentMethod];
        $method = null;

        switch ($paymentMethod) {
            case Mundipagg_Paymentmodule_Model_Boleto::CODE:
                $method = 'boleto';
                break;
            case Mundipagg_Paymentmodule_Model_Boletocc::CODE:
                $method = 'boletocc';
                break;
            case Mundipagg_Paymentmodule_Model_Creditcard::CODE:
                $method = 'creditcard';
                break;
            case Mundipagg_Paymentmodule_Model_Twocreditcards::CODE:
                $method = 'creditcard';
                break;
            case Mundipagg_Paymentmodule_Model_Voucher::CODE:
                $method = 'voucher';
                break;
            default:
                $method = explode('_', $paymentMethod);
                $method = $method[1];
                break;
        }

        $addressLines = preg_replace('/\r|\n/', ',', trim($billingAddress->getStreetFull()));
        $addressLines = explode(',', $addressLines);

        foreach ($paymentInformation[$method] as $key => $value) {
            $paymentInformation[$method][$key]['billing'] = array(
                'zip_code' => str_replace('-', '', $billingAddress->getPostcode()),
                'city' => $billingAddress->getCity(),
                'state' => $billingAddress->getRegionCode(),
                'country' => $billingAddress->getCountry(),
                'line_1' => $addressLines[1] . ',' . $addressLines[0] . ',' . $addressLines[3]
            );
        }

        $result = array();

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

    protected function getShippingRequest($shippingInformation)
    {

        /*
         * In case of a virtual product, the shipping address does not exists.
         * Therefore, the shippingRequests should be null.
         */

        $address = $shippingInformation->getAddress();
        if ($address === AddressHelper::NONE) {
            return null;
        }

        $shippingRequest = new CreateShippingRequest();

        $shippingRequest->amount = round($shippingInformation->getAmount());
        $shippingRequest->description = $shippingInformation->getDescription();
        $shippingRequest->address = $this->getCreateAddressRequest($address);
        $shippingRequest->type =  $shippingInformation->getMethod();

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

        if ($customer = Mage::helper('customer')->getCustomer()) {
            return array(
                'name' => $customer->getName(),
                'email' => $customer->getEmail()
            );
        }

        return null;
    }

    /**
     * @return CreateAddressRequest
     */
    protected function getAddressFromSession()
    {
        $address = Mage::helper('paymentmodule/address')->getCustomerAddressInformation();

        $addressRequest = new CreateAddressRequest();

        $addressRequest->street = $address->getStreet();
        $addressRequest->number = $address->getNumber();
        $addressRequest->complement = $address->getComplement();
        $addressRequest->neighborhood = $address->getNeighborhood();
        $addressRequest->city = $address->getCity();
        $addressRequest->state = $address->getState();
        $addressRequest->country = $address->getCountry();
        $addressRequest->zipCode = $address->getZipCode();
        $addressRequest->line1 = $address->getData('line_1');

        return $addressRequest;
    }

    /**
     * @param $customer
     * @return CreateCustomerRequest
     */
    protected function getCustomerFromMultiBuyer($customer)
    {
        $multiBuyerConfig = Mage::getModel('paymentmodule/config_multibuyer');
        if (!$multiBuyerConfig->isEnabled()) {
            return null;
        }

        $customerRequest = new CreateCustomerRequest();

        $document = preg_replace('/[^0-9]/', '', $customer['multiBuyerTaxvat']);

        $type = 'individual';
        if (strlen($document) > 11) {
            $type = 'company';
        }

        $customerRequest->name = $customer['multiBuyerName'];
        $customerRequest->email = $customer['multiBuyerEmail'];
        $customerRequest->document = $document;
        $customerRequest->address = $this->getAddressFromMultiBuyer($customer);
        $customerRequest->type = $type;

        $rawPhone = $customer['multiBuyerPhone'];
        $phoneHelper = Mage::helper('paymentmodule/phone');
        $phoneInfo = $phoneHelper->extractPhoneVarienFromRawPhoneNumber($rawPhone);

        $customerRequest->phones = $this->getCreatePhonesRequest($phoneInfo);

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
