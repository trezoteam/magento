<?php

class Mundipagg_Paymentmodule_ChargeController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }

    public function indexAction()
    {
        if (Mage::app()->getRequest()->isPost()) {
            $body = json_decode(Mage::app()->getRequest()->getRawBody());

            $orderId = $body->orderId;

            $collection = Mage::getResourceModel('sales/order_collection')
                ->join(['b' => 'sales/order_payment'],
                    'main_table.entity_id = b.parent_id',
                    ['additional_information' => 'additional_information']
                )
                ->addFieldToFilter('main_table.entity_id', $orderId);

            $aditional = [];
            foreach ($collection as $order) {
                $aditional = unserialize($order->additional_information);
            }

           if (!isset($aditional['mundipagg_payment_module_charges'])) {

               $response = new stdClass();
               $response->message = 'Invalid Order';
               $response->status = 404;

               $this->getResponse()
                   ->clearHeaders()
                   ->setHeader('HTTP/1.0', $response->status , true)
                   ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                   ->setBody(json_encode($response));
                return;
           }

           $charges = $aditional['mundipagg_payment_module_charges'];

           if (!isset($charges[$body->id])) {
               $response = new stdClass();
               $response->message = 'Invalid Charge';
               $response->status = 404;

               $this->getResponse()
                   ->clearHeaders()
                   ->setHeader('HTTP/1.0', $response->status , true)
                   ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                   ->setBody(json_encode($response));
               return;
           }

            $charge = $body;
            $charge->amount = $charge->operationType == "total" ? $charge->centsValue : $charge->operationValue;

            $api = Mage::getModel('paymentmodule/api_order');
            $response = $api->updateCharge($charge);

            if(is_string($response)) {
                $responseMsg = $response;
                $response = new stdClass();
                $response->message = "Operation failed.";
                $response->details = $responseMsg;
                $response->status = 403;

                $this->getResponse()
                    ->clearHeaders()
                    ->setHeader('HTTP/1.0', 200 , true)
                    ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                    ->setBody(json_encode($response));
                return;
            }

            $response = new stdClass();
            $response->message = 'Success';
            $response->status = 200;

            $this->getResponse()
                ->clearHeaders()
                ->setHeader('HTTP/1.0', $response->status , true)
                ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                ->setBody(json_encode($response));
            return;
        }
    }
}
