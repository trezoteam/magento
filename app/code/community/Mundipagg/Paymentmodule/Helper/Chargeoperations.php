<?php

class Mundipagg_Paymentmodule_Helper_Chargeoperations extends Mage_Core_Helper_Abstract
{
    /**
     * @param $methodName
     * @param $webHook
     */
    public function paidMethods($methodName, $webHook)
    {
        $orderId = $webHook->code;
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $moneyHelper = Mage::helper('paymentmodule/monetary');

        $chargePaid = $webHook->paid_amount / 100;
        $comment = "total paid: " . $moneyHelper->moneyFormat($chargePaid);

        $totalPaid = $order->getBaseTotalPaid() + $chargePaid;

        $order
            ->setBaseTotalPaid($totalPaid)
            ->setTotalPaid($totalPaid)
            ->save();

        $this->updateChargeInfo($methodName, $webHook, $comment);
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

        $comment = $this->joinComments($type, $webHook->id, $comment);
        $this->addOrderHistory($orderId, $comment);
    }

    /**
     * Join comments to insert into order history
     * @param string $type
     * @param int $chargeId
     * @param string $extraComment
     * @return string
     */
    public function joinComments($type, $chargeId, $extraComment)
    {
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');

        $type = 'charge' . ucfirst($type);
        $comment = $orderEnum->{$type}();
        $comment .= $extraComment;
        $comment .= ' (' . $chargeId . ')';

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
}