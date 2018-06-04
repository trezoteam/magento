<?php

class Mundipagg_Paymentmodule_Helper_Address extends Mage_Core_Helper_Abstract
{
    public function getCustomerAddressInformation()
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $standard->getCheckoutSession();

        $orderId = $checkoutSession->getLastOrderId();
        $order = $standard->getOrderByOrderId($orderId);
        $billingAddress = $order->getBillingAddress();
        $regionId = $billingAddress->getRegionId();
        $address = new Varien_Object();

        list(
            $customerStreet,
            $customerNumber,
            $customerComplement,
            $customerNeighborhood
            ) = $billingAddress->getStreet();

        $address->setStreet($customerStreet);
        $address->setNumber($customerNumber);
        $address->setComplement($customerComplement);
        $address->setNeighborhood($customerNeighborhood);
        $address->setCity($billingAddress->getCity());
        $address->setState($this->getStateByRegionId($regionId));
        $address->setCountry($billingAddress->getCountryId());
        $address->setZipCode($billingAddress->getPostcode());
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
