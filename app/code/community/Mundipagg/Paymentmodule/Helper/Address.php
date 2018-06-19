<?php

class Mundipagg_Paymentmodule_Helper_Address extends Mage_Core_Helper_Abstract
{

    const NULL_ADDRESS_PLACE_HOLDER = '-';

    public function getCustomerAddressInformation()
    {
       return $this->getAddress('getBillingAddress');
    }

    public function getShippingAddressInformation($order = null) {
        return $this->getAddress('getShippingAddress', $order);
    }

    protected function getAddress($method, $order = null) {
        $standard = Mage::getModel('paymentmodule/standard');
        $addressModel = Mage::getModel('paymentmodule/config_address');
        $checkoutSession = $standard->getCheckoutSession();

        $orderId = $checkoutSession->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);
        $baseAddress = $order->$method();
        $streetLines = $baseAddress->getStreet();
        $regionId = $baseAddress->getRegionId();
        $address = new Varien_Object();

        $customerStreet =
            $this->splitStreetLines($streetLines, $addressModel->getStreet());

        $customerNumber =
            $this->splitStreetLines($streetLines, $addressModel->getNumber());

        $customerComplement =
            $this->splitStreetLines($streetLines, $addressModel->getComplement());

        $customerNeighborhood =
            $this->splitStreetLines($streetLines, $addressModel->getNeighborhood());

        $address->setStreet($customerStreet);
        $address->setNumber($customerNumber);
        $address->setComplement($customerComplement);
        $address->setNeighborhood($customerNeighborhood);
        $address->setCity($baseAddress->getCity());
        $address->setState($this->getStateByRegionId($regionId));
        $address->setCountry($baseAddress->getCountryId());
        $address->setZipCode($baseAddress->getPostcode());
        $address->setMetadata(null);

        return $address;
    }

    /**
     * Return state code
     * @example $this->getStateByRegionId(502) //return "RJ"
     * @param int $regionId
     * @return string
     */
    protected function getStateByRegionId($regionId)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $region = $standard->getRegionModel()->load($regionId);

        return $region->getCode();
    }

    protected function splitStreetLines($streetLines, $streetId)
    {
        $street = explode('_', $streetId);

        if (isset($street[1]) && isset($streetLines[$street[1] -1])) {
            return $streetLines[$street[1] -1];
        }

        return self::NULL_ADDRESS_PLACE_HOLDER;
    }
}
