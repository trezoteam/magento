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

    private function addHistory()
    {

    }

    private function updateCharge()
    {

    }
}
