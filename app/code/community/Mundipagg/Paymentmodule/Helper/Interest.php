<?php

class Mundipagg_Paymentmodule_Helper_Interest extends Mage_Core_Helper_Abstract
{
    public function getInterestValue($installmentNum,$orderTotal,$cards = null)
    {
        $installmentHelper = Mage::helper('paymentmodule/installment');
        $allInstallments = $installmentHelper->getInstallments($orderTotal,$cards);
        $installmentInterest = 0;
        foreach($allInstallments['default'] as $installment) {
            if ($installment['times'] == $installmentNum) {
                $installmentInterest = $installment['interest'];
                break;
            }
        }
        $interest = $orderTotal * ($installmentInterest / 100);
        return round($interest,2);
    }
}