<?php

class Mundipagg_Paymentmodule_Helper_Charge extends Mage_Core_Helper_Abstract
{
    public function updateStatus($chargeWebHook, $action)
    {
        $chargeCore = Mage::getModel('paymentmodule/core_charge');

        try {
            $chargeCore->{$action}($chargeWebHook);
        } catch (\Exception $e) {
            $a = 1;
            $a = 2;
            // do something with the error
        }
    }
}
