<?php

class Mundipagg_Paymentmodule_Helper_Chargeoperations extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $methodName
     * @param stdClass $charge
     */
    public function paidMethods($methodName, $charge, $manual = false)
    {
        $orderId = $charge->code;
        $chargeId = $charge->id;

        if (!$this->isChargeAlreadyUpdated($chargeId, $orderId, $methodName)) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

            $moneyHelper = Mage::helper('paymentmodule/monetary');
            $invoiceHelper = Mage::helper('paymentmodule/invoice');

            $paidAmount = $this->getChargePaidAmount($charge);
            $formattedPaidAmount = $moneyHelper->toCurrencyFormat($paidAmount);
            if ($manual) {
                $formattedPaidAmount =
                    'Updated manually through the module. Value: ' .
                    $formattedPaidAmount;
            }

            $invoiceHelper->addInvoiceToOrder($order, $paidAmount);
            $this->updateChargeInfo($methodName, $charge, $formattedPaidAmount);
        }
    }

    /**
     * @param string $methodName
     * @param stdClass $charge
     */
    public function canceledMethods($methodName, $charge, $extraComment = '')
    {
        $orderId = $charge->code;
        $order =
            Mage::getModel('sales/order')
                ->loadByIncrementId($orderId);

        $moneyHelper = Mage::helper('paymentmodule/monetary');
        $canceledAmount = $this->getChargeCanceledAmount($charge);

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

        $this->updateChargeInfo($methodName, $charge, $extraComment);
    }

    /**
     * Common operations for all charges
     * @param string $type charge type (paid, created, etc)
     * @param stdClass $charge Full webhook object
     * @param string $comment additional comments
     */
    public function updateChargeInfo($type, $charge, $comment = '')
    {
        $orderId = $charge->code;
        $charges[] = $charge;

        $standard = Mage::getModel('paymentmodule/standard');
        $standard->addChargeInfoToAdditionalInformation($charges, $orderId);

        $comment = $this->joinComments($type, $charge, $comment);
        $this->addOrderHistory($orderId, $comment);
    }

    /**
     * @param stdClass $charge
     * @return int
     */
    protected function getChargePaidAmount($charge)
    {
        $field = 'paid_amount';
        if (!isset($charge->$field)) {
            $field = 'amount';
        }

        return $charge->$field / 100;
    }

    /**
     * @param stdClass $charge
     * @return int
     */
    protected function getChargeCanceledAmount($charge)
    {
        if (isset($charge->canceled_amount)) {
            return $charge->canceled_amount / 100;
        }

        return 0;
    }

    /**
     * Join comments to insert into order history
     * @param string $type
     * @param stdClass $charge
     * @param string $extraComment
     * @return string
     */
    public function joinComments($type, $charge, $extraComment)
    {
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');

        $type = 'charge' . ucfirst($type);
        $comment = $orderEnum->{$type}();
        $comment .= $extraComment . '<br>';
        $comment .= 'Charge id: ' . $charge->id . '<br>';
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

    public function isChargeAlreadyUpdated($chargeId, $orderId, $chargeType)
    {
        $standard = Mage::getModel('paymentmodule/standard');

        $additionalInfo =
            $standard->getAdditionalInformationForOrder($orderId);

        if (!empty($additionalInfo['mundipagg_payment_module_charges'][$chargeId])) {
            $status =
                $additionalInfo['mundipagg_payment_module_charges'][$chargeId]['status'];

            if (
                $status === $chargeType ||
                $chargeType === 'created' && $status != 'created'
            ) {
                return true;
            }
        }

        return false;
    }
}
