<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

class Mundipagg_Paymentmodule_WebhookController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if (Mage::app()->getRequest()->isPost()) {
            $body = json_decode(Mage::app()->getRequest()->getRawBody());

            $webhookInfo = explode('.', $body->type);
            $webhookType  = $webhookInfo[0];
            $webhookAction = $webhookInfo[1];

            switch ($webhookType) {
                case 'order':
                    $this->webhookOrderUpdate($body->data, $webhookAction);
                    break;
                case 'charge':
                    $this->webhookChargeUpdate($body->data, $webhookAction);
                    break;
                default:
                    throw new \Exception('fuuuuuu');
            }
        }
    }

    private function webhookOrderUpdate($order, $action)
    {
        $orderHelper = Mage::helper('paymentmodule/order');
        $orderHelper->updateStatus($order, $action);
    }

    private function webhookChargeUpdate($charge, $action)
    {
        $chargeHelper = Mage::helper('paymentmodule/charge');
        $chargeHelper->updateStatus($charge, $action);
    }
}
