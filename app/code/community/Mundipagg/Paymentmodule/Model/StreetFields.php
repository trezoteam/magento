<?php

class Mundipagg_Paymentmodule_Model_StreetFields
{

    public function toOptionArray()
    {
        $fieldsNumber = Mage::getStoreConfig('customer/address/street_lines');

        for ($i = 1; $i <= $fieldsNumber; $i++) {
            $streetLines[$i -1] = 'street_' . $i;
        }

        return $streetLines;
    }
}
