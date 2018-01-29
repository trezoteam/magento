<?php

class Mundipagg_Paymentmodule_Model_Core_Order  extends Mundipagg_Paymentmodule_Model_Core_Base
{
    //Do nothing
    protected function created($webHook)
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

    protected function canceled($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($webHook->code);

        if ($order->canUnhold()) {
            $order->unhold();
        }

        if ($this->cancelInvoices($order)) {
            $this->closeOrder($order);
        }

        $order->cancel()->save();
    }


    protected function paymentFailed($webHook)
    {
    }

    public function cancelInvoices($order)
    {
        $invoices = $this->getInvoicesAllowedToCancel($order);

        // Refund invoices and Credit Memo
        if (!empty($invoices)) {

            $service = Mage::getModel('sales/service_order', $order);

            foreach ($invoices as $invoice) {
                $this->closeInvoice($invoice);
                $totalRefunded = $order->getBaseTotalRefunded();
                if (!$totalRefunded) {
                    $this->createCreditMemo($invoice, $service);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param object $order
     * @return boolean
     */
    private function closeOrder($order){
        $order->setData('state', Mage_Sales_Model_Order::STATE_CLOSED);
        $order->setStatus(Mage_Sales_Model_Order::STATE_CLOSED);
        $order->sendOrderUpdateEmail();
        $order->save();
    }

    /**
     * @param object $invoice
     * @return boolean
     */
    private function closeInvoice($invoice){
        $invoice->setState(Mage_Sales_Model_Order_Invoice::STATE_CANCELED);
        $invoice->save();
    }

    /**
     * @param object $invoice
     * @return boolean
     */
    private function createCreditMemo($invoice, $service)
    {
        $creditmemo = $service->prepareInvoiceCreditmemo($invoice);
        $creditmemo->setOfflineRequested(true);
        $creditmemo->register()->save();

    }

    private function getInvoicesAllowedToCancel($order)
    {
        $invoices= [];

        foreach ($order->getInvoiceCollection() as $invoice) {
            // We check if invoice can be refunded
            if ($invoice->canRefund() && !$invoice->isCanceled()) {
                $invoices[] = $invoice;
            }
        }

        return $invoices;
    }
}
