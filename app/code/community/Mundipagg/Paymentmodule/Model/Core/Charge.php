<?php

/**
 * Class Mundipagg_Paymentmodule_Model_Core_Charge
 */
class Mundipagg_Paymentmodule_Model_Core_Charge extends Mundipagg_Paymentmodule_Model_Core_Base
{
    /**
     * @param $webHook
     * @throws Exception
     */
    protected function created($webHook)
    {
        $orderId = $webHook->code;

        $standard = Mage::getModel('paymentmodule/standard');
        $charge[] = $webHook;
        $standard->addChargeInfoToAdditionalInformation($charge, $orderId);
        $this->addOrderHistory($orderId, $charge[0]->id);
    }

    /**
     * @param $webHook
     */
    protected function paid($webHook)
    {
        $orderId = $webHook->code;
        $amount = $webHook->amount;
        $transactionId = $webHook->id;

    }

    /**
     * @param $webHook
     */
    protected function overpaid($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');

        $orderId = $webHook->code;
        $amount = $webHook->amount;
        $transactionId = $webHook->id;
        $paymentMethod = $webHook->payment_method;

        $order = $standard->getOrderByIncrementOrderId($orderId);
        $payment = $order->getPayment();
    }

    private function addOrderHistory($orderId, $chargeId)
    {
        $standard = Mage::getModel('paymentmodule/enum_orderhistory');
        $comment = $standard::CHARGE_CREATED;
        $comment .= " (" . $chargeId . ")";

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $order->addStatusHistoryComment($comment, false);
        $order->save();
    }
}
