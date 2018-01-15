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
        $transactionId = $webHook->id;
        $amount = $webHook->amount;

        $this->addTransactionToOrder($orderId, $transactionId);
        $this->addInvoiceToOrder($orderId, $amount);
    }

    /**
     * @param $webHook
     */
    protected function paid($webHook)
    {
        $orderId = $webHook->code;
        $amount = $webHook->amount;
        $transactionId = $webHook->id;

        $this->captureTransaction($orderId, $transactionId, $amount);
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
        $this->getPaymentMethodModel($paymentMethod);

        $order = $standard->getOrderByIncrementOrderId($orderId);
        $payment = $order->getPayment();
    }

    /**
     * @param $orderId
     * @param $transactionId
     * @throws Exception
     */
    private function addTransactionToOrder($orderId, $transactionId)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($orderId);

        $transaction = Mage::getModel('sales/order_payment_transaction');
        $transaction->setTxnId($transactionId);
        $transaction->setOrderPaymentObject($order->getPayment());

        $transaction->save();
    }

    /**
     * @param $orderId
     * @param $amount
     */
    private function addInvoiceToOrder($orderId, $amount)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($orderId);

        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
        $invoice->setGrandTotal($amount/100);
        $invoice->register();
        $invoice->getOrder()->setIsInProcess(true);
        $order->save();

        Mage::getModel('core/resource_transaction')
            ->addObject($invoice)
            ->addObject($invoice->getOrder())
            ->save();
    }

    /**
     * @param $orderId
     * @param $transactionId
     * @param $amount
     */
    private function captureTransaction($orderId, $transactionId, $amount)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($orderId);
        $payment = $order->getPayment();
    }

    /**
     * @param $orderId
     * @param $amount
     * @return mixed
     * @throws Mage_Core_Exception
     */
    private function createInvoice($orderId, $amount)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($orderId);

        if (!$order->canInvoice()) {
            Mage::throwException('CANNOT CREATE INVOICE');
        }

        // reset total paid because invoice generation set order total_paid also
        $order->setBaseTotalPaid(null)->setTotalPaid(null)->save();
        $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();

        if (!$invoice->getTotalQty()) {
            Mage::throwException('CANNOT CREATE INVOICE WITHOUT PRODUCTS');
        }

        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
        $invoice->register();
        $invoice->getOrder()->setCustomerNoteNotify(false);
        $invoice->getOrder()->setIsInProcess(true);
        $invoice->setCanVoidFlag(true);
        $invoice->pay();

        try {
            $transactionSave = Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder());
            $transactionSave->save();
        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        return $invoice;
    }
}
