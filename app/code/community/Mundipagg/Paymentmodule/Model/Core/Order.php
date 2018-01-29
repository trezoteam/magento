<?php

class Mundipagg_Paymentmodule_Model_Core_Order  extends Mundipagg_Paymentmodule_Model_Core_Base
{
    //Do nothing
    protected function created($webHook)
    {
    }

    protected function canceled($webHook)
    {
    }

    /**
     * Set order status as processing
     * Order invoice is created by charge webhooks
     * @param stdClass $webHook
     */
    protected function paid($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($webHook->code);
        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, '', true);
        $order->save();
    }

    protected function paymentFailed($webHook)
    {
    }
}
