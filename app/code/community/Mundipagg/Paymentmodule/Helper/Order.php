<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_Helper_Order extends Mage_Core_Helper_Abstract
{
    /**
     * Change the status of an order, fetched by the code field in order object, on $orderResponse param.
     * If $statusCode is null, the new status of the order will be defined by the status field on the order object.
     *
     * @param GetOrderResponse $orderResponse
     * @param string|null $statusCode If null, get the status from order object.
     * @throws Exception
     */
    public function updateStatus(GetOrderResponse $orderResponse, $statusCode = null)
    {
        $status = $statusCode;
        if (!$status) {
            $statusMap = $this->getStatusMap();
            $status = $statusMap[strtolower($orderResponse->status)];
        }
        $newHistory = new Mage_Sales_Model_Order_Status_History();
        $newHistory->setStatus($status);

        $order = new Mage_Sales_Model_Order();
        $order->loadByIncrementId($orderResponse->code);
        $order->addStatusHistory($newHistory);
        $order->save();
    }

    /**
     * Returns an array map for order object statuses.
     *
     * @return array
     */
    public function getStatusMap()
    {
        return [
            'pending'   => 'pending',
            'paid'      => Mage_Sales_Model_Order::STATE_PROCESSING,
            'canceled'  => Mage_Sales_Model_Order::STATE_CANCELED,
            'failed'    => Mage_Sales_Model_Order::STATE_CANCELED,
        ];
    }
}