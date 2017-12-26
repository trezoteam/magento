<?php

use MundiAPILib\Models\GetChargeResponse;

class Mundipagg_Paymentmodule_Helper_Charge extends Mage_Core_Helper_Abstract
{
    public function updateStatus(GetChargeResponse $chargeResponse)
    {
        $order = new Mage_Sales_Model_Order();
        $order->loadByIncrementId($chargeResponse->code);
        $lastStatus = $order->getStatusHistoryCollection()->getFirstItem()->getData('status');

        $newHistory = new Mage_Sales_Model_Order_Status_History();
        $newHistory->setStatus($lastStatus);
        $newHistory->setComment($chargeResponse->status);

        $order->addStatusHistory($newHistory);
        $order->save();
    }
}