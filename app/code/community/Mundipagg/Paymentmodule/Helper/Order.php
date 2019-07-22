<?php

class Mundipagg_Paymentmodule_Helper_Order extends Mage_Core_Helper_Abstract
{
    public function updateStatus($orderWebHook, $action)
    {
        $orderCore = Mage::getModel('paymentmodule/core_order');

        try {
            $orderCore->{$action}($orderWebHook);
        } catch (\Exception $e) {
            //@todo do something with the error
        }
    }

    public function getOrderPayment($orderId)
    {
        $payment = Mage::getResourceSingleton('sales/order_payment_collection')
            ->setOrderFilter($orderId)
            ->getFirstItem();

        return $payment;
    }
}