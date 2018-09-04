<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateVoucherPaymentRequest;

class Mundipagg_Paymentmodule_Model_Api_Voucher extends Mundipagg_Paymentmodule_Model_Api_Standard
{
    public function getPayment($paymentInfo)
    {
        $voucherConfig = Mage::getModel('paymentmodule/config_voucher');
        $monetary = Mage::helper('paymentmodule/monetary');

        $bank = $voucherConfig->getBank();
        $instructions = $voucherConfig->getInstructions();
        $dueAt = $voucherConfig->getDueAt();

        $result = [];

        foreach ($paymentInfo as $payment) {
            $paymentRequest = new CreatePaymentRequest();

            $voucherPaymentRequest = new CreateVoucherPaymentRequest();

            $voucherPaymentRequest->bank = $bank;
            $voucherPaymentRequest->instructions = $instructions;
            $voucherPaymentRequest->dueAt = $dueAt;

            $paymentRequest->paymentMethod = 'voucher';
            $paymentRequest->voucher = $voucherPaymentRequest;
            $paymentRequest->amount = round($monetary->toCents($payment['value']));
            $paymentRequest->customer = $this->getCustomer($payment);
            $paymentRequest->currency = $this->getCurrentCurrencyCode();

            $result[] = $paymentRequest;
        }

        return $result;
    }
}
