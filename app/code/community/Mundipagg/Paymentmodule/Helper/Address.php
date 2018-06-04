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
        $checkoutSession = $standard->getCheckoutSession();

        $orderId = $checkoutSession->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);
        $baseAddress = $order->$method();
        $regionId = $baseAddress->getRegionId();
        $address = new Varien_Object();

        list(
            $customerStreet,
            $customerNumber,
            $customerComplement,
            $customerNeighborhood
            ) = $baseAddress->getStreet();

        if (strlen($customerStreet) === 0) {
            $customerStreet = self::NULL_ADDRESS_PLACE_HOLDER;
        }
        if (strlen($customerNumber) === 0) {
            $customerNumber = self::NULL_ADDRESS_PLACE_HOLDER;
        }
        if (strlen($customerNeighborhood) === 0) {
            $customerNeighborhood = self::NULL_ADDRESS_PLACE_HOLDER;
        }

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
}
