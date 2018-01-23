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
        $this->updateChargeInfo(__FUNCTION__, $webHook);
    }

    /**
     * @param $webHook
     */
    protected function paid($webHook)
    {
        $orderId = $webHook->code;
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $moneyHelper = Mage::helper('paymentmodule/monetary');

        $chargePaid = $webHook->paid_amount / 100;
        $comment = "total paid: " . $moneyHelper->moneyFormat($chargePaid);

        $totalPaid = $order->getBaseTotalPaid() + $chargePaid;

        $order->setBaseTotalPaid($totalPaid)
            ->setTotalPaid($totalPaid)
            ->save();

        $this->updateChargeInfo(__FUNCTION__, $webHook, $comment);
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

    /**
     * Common operations for all charges
     * @param string $type charge type (paid, created, etc)
     * @param $webHook full webhook object
     * @param string $comment additional comments
     */
    private function updateChargeInfo($type, $webHook, $comment = '')
    {
        $orderId = $webHook->code;
        $charge[] = $webHook;

        $standard = Mage::getModel('paymentmodule/standard');
        $standard->addChargeInfoToAdditionalInformation($charge, $orderId);

        $comment = $this->joinComments($type, $webHook->id, $comment);
        $this->addOrderHistory($orderId, $comment);
    }

    /**
     * Join comments to insert into order history
     * @param string $type
     * @param int $chargeId
     * @param strin $extraComment
     * @return string
     */
    private function joinComments($type, $chargeId, $extraComment)
    {
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');

        $type = "charge" . ucfirst($type);
        $comment = $orderEnum->{$type}();
        $comment .= $extraComment;
        $comment .= " (" . $chargeId . ")";

        return $comment;
    }

    /**
     * Add comments to order history
     * @param int $orderId
     * @param strin $comment
     */
    private function addOrderHistory($orderId, $comment)
    {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $order->addStatusHistoryComment($comment, false);
        $order->save();
    }
}
