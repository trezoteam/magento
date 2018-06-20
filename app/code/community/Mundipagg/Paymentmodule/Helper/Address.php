<?php

class Mundipagg_Paymentmodule_Helper_Address extends Mage_Core_Helper_Abstract
{
    const NULL_ADDRESS_PLACE_HOLDER = '-';

    public function getCustomerAddressInformation()
    {
       return $this->getAddress('getBillingAddress');
    }

    public function getShippingAddressInformation($order = null)
    {
        return $this->getAddress('getShippingAddress', $order);
    }

    protected function getAddress($method)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $addressModel = Mage::getModel('paymentmodule/config_address');
        $checkoutSession = $standard->getCheckoutSession();

        $orderId = $checkoutSession->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);
        $baseAddress = $order->$method();
        $region = $this->getStateByRegionId($baseAddress->getRegionId());
        $address = new Varien_Object();

        $customerAddress =
            $this->fillCustomerAddressArray(
                $baseAddress->getStreet(),
                $addressModel
            );

        $address->setStreet($customerAddress[0]);
        $address->setNumber($customerAddress[1]);
        $address->setComplement($customerAddress[2]);
        $address->setNeighborhood($customerAddress[3]);
        $address->setCity($baseAddress->getCity());
        $address->setState($region);
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

    protected function fillCustomerAddressArray($streetLines, $addressModel)
    {
        $methods = ['getStreet','getNumber','getComplement','getNeighborhood'];
        $customerAddress = [];

        array_walk($methods, function($method, $index)
            use (&$customerAddress, $addressModel, $streetLines) {
            $customerAddress[$index] = self::NULL_ADDRESS_PLACE_HOLDER;
            $streetLinesIndex = $addressModel->$method();

            if (
                isset($streetLines[$streetLinesIndex]) &&
                $streetLines[$streetLinesIndex] !== ''
            ) {
                $customerAddress[$index] = $streetLines[$streetLinesIndex];
            }
        });

        return $customerAddress;
    }
}
