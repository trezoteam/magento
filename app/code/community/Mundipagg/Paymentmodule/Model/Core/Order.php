<?php

class Mundipagg_Paymentmodule_Model_Core_Order  extends Mundipagg_Paymentmodule_Model_Core_Base
{
    //Do nothing
    protected function created($webHook)
    {
    }

    /**
     * Set order status as processing
     * Order invoice is created by charge webhook
     * @param stdClass $webHook
     * @throws Varien_Exception
     */
    protected function paid($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($webHook->code);

        if ($order->getState() != Mage_Sales_Model_Order::STATE_PROCESSING) {
            $order
                ->setState(
                    Mage_Sales_Model_Order::STATE_PROCESSING,
                    true,
                    '',
                    true
                );
            $order->save();
        }
    }

    /**
     * @param stdClass $webHook
     * @throws Varien_Exception
     */
    protected function canceled($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $invoiceHelper = Mage::helper('paymentmodule/invoice');

        $order = $standard->getOrderByIncrementOrderId($webHook->code);

        if ($order->canUnhold()) {
            $order->unhold();
        }

        if ($invoiceHelper->cancelInvoices($order)) {
            $this->closeOrder($order);
        }

        $order
            ->setState(
                Mage_Sales_Model_Order::STATE_CANCELED,
                true,
                '',
                true
            );
        $order->save();
    }


    protected function paymentFailed($webHook)
    {
        $this->canceled($webHook);
    }


    /**
     * @param object $order
     */
    protected function closeOrder($order)
    {
        $order->setData('state', Mage_Sales_Model_Order::STATE_CLOSED);
        $order->setStatus(Mage_Sales_Model_Order::STATE_CLOSED);
        $order->sendOrderUpdateEmail();
        $order->save();
    }
}
