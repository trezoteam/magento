<?php

class Mundipagg_Paymentmodule_Helper_Chargeoperations extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $methodName
     * @param stdClass $webHook
     */
    public function paidMethods($methodName, $webHook)
    {
        $orderId = $webHook->code;
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $moneyHelper = Mage::helper('paymentmodule/monetary');

        $paidAmount = $this->getWebHookPaidAmount($webHook);
        $formattedPaidAmount = $moneyHelper->toCurrencyFormat($paidAmount);

        $this->addInvoiceToOrder($order, $paidAmount);
        $this->updateChargeInfo($methodName, $webHook, $formattedPaidAmount);
    }

    /**
     * @param string $methodName
     * @param stdClass $webHook
     */
    public function canceledMethods($methodName, $webHook, $extraComment = '')
    {
        $orderId = $webHook->code;
        $order =
            Mage::getModel('sales/order')
                ->loadByIncrementId($orderId);

        $moneyHelper = Mage::helper('paymentmodule/monetary');
        $canceledAmount = $this->getWebHookCanceledAmount($webHook);

        if ($canceledAmount) {
            $extraComment .= $moneyHelper->toCurrencyFormat($canceledAmount);
        }

        if ($order->getTotalPaid() > 0) {
            $totalRefunded = $order->getTotalRefunded() + $canceledAmount;
            $order
                ->setTotalRefunded($totalRefunded)
                ->setBaseTotalRefunded($totalRefunded)
                ->save();
        }

        $this->updateChargeInfo($methodName, $webHook, $extraComment);
    }

    /**
     * Common operations for all charges
     * @param string $type charge type (paid, created, etc)
     * @param stdClass $webHook Full webhook object
     * @param string $comment additional comments
     */
    public function updateChargeInfo($type, $webHook, $comment = '')
    {
        $orderId = $webHook->code;
        $charge[] = $webHook;

        $standard = Mage::getModel('paymentmodule/standard');
        $standard->addChargeInfoToAdditionalInformation($charge, $orderId);

        $comment = $this->joinComments($type, $webHook, $comment);
        $this->addOrderHistory($orderId, $comment);
    }

    /**
     * @param stdClass $webHook
     * @return int
     */
    private function getWebHookPaidAmount($webHook)
    {
        if (isset($webHook->paid_amount)) {
            return $webHook->paid_amount / 100;
        }

        return 0;
    }

    /**
     * @param stdClass $webHook
     * @return int
     */
    private function getWebHookCanceledAmount($webHook)
    {
        if (isset($webHook->canceled_amount)) {
            return $webHook->canceled_amount / 100;
        }

        return 0;
    }


    /**
     * Join comments to insert into order history
     * @param string $type
     * @param stdClass $webHook
     * @param string $extraComment
     * @return string
     */
    public function joinComments($type, $webHook, $extraComment)
    {
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');

        $type = 'charge' . ucfirst($type);
        $comment = $orderEnum->{$type}();
        $comment .= $extraComment . '<br>';
        $comment .= 'Webhook Info: <br>';
        $comment .= 'Charge id: ' . $webHook->id . '<br>';
        $comment .= 'Order id: ' . $webHook->order->id . '<br>';
        $comment .= 'Event: ' . $type;

        return $comment;
    }

    /**
     * Add comments to order history
     * @param int $orderId
     * @param string $comment
     */
    public function addOrderHistory($orderId, $comment)
    {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $order->addStatusHistoryComment($comment, false);
        $order->save();
    }


    private function addInvoiceToOrder($order, $amount)
    {
        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
        $invoice->register();
        $invoice->setBaseGrandTotal($amount);
        $invoice->setGrandTotal($amount);
        $invoice->setRequestedCaptureCase('online')->setCanVoidFlag(false)->pay();
        $order->save();

        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();
    }
}