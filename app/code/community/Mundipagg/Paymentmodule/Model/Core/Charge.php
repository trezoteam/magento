<?php

/**
 * Class Mundipagg_Paymentmodule_Model_Core_Charge
 */
class Mundipagg_Paymentmodule_Model_Core_Charge extends Mundipagg_Paymentmodule_Model_Core_Base
{
    /**
     * Common operations for all charges
     * @param string $type charge type (paid, created, etc)
     * @param $webHook
     */
    private function chargeUpdate($type, $webHook)
    {
        $orderId = $webHook->code;

        $standard = Mage::getModel('paymentmodule/standard');
        $charge[] = $webHook;
        $standard->addChargeInfoToAdditionalInformation($charge, $orderId);

        $this->addOrderHistory($orderId, $webHook->id, $type);
    }

    /**
     * @param $webHook
     * @throws Exception
     */
    protected function created($webHook)
    {
        $this->chargeUpdate(__FUNCTION__, $webHook);
    }

    /**
     * @param $webHook
     */
    protected function paid($webHook)
    {
        $this->chargeUpdate(__FUNCTION__, $webHook);
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

    private function addOrderHistory($orderId, $chargeId, $chargeType)
    {
        $standard = Mage::getModel('paymentmodule/enum_orderhistory');

        $type = "charge" . ucfirst($chargeType);

        $comment = $standard->{$type}();
        $comment .= " (" . $chargeId . ")";

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $order->addStatusHistoryComment($comment, false);
        $order->save();
    }
}
