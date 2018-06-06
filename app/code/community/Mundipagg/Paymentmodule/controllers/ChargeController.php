<?php

class Mundipagg_Paymentmodule_ChargeController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        $r = Mage::app()->getRequest();
        $r = Mage::app()->getRequest()->getParam('source');
        $r = json_decode(Mage::app()->getRequest()->getRawBody());
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }

    public function indexAction()
    {
        try {
            if (Mage::app()->getRequest()->isPost()) {
                $body = json_decode(Mage::app()->getRequest()->getRawBody());

                //validating password.
                $adminUser = Mage::getModel('admin/user')->loadByUsername($body->username);
                list($adminPassword,$adminSalt) = explode(':',$adminUser->getPassword());
                $inputPassword = md5($adminSalt . $body->credential);

                if($inputPassword !== $adminPassword) {
                    $response = new stdClass();
                    $response->message = 'Invalid credentials';
                    $response->status = 403;

                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('HTTP/1.0', 200 , true)
                        ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                        ->setBody(json_encode($response));
                    return;
                }

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
                    $response->message = "Operation failed";
                    $response->details = $responseMsg;
                    $response->status = 403;

                    $this->getResponse()
                        ->clearHeaders()
                        ->setHeader('HTTP/1.0', 200 , true)
                        ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                        ->setBody(json_encode($response));
                    return;
                }

                /**
                 * @var Mundipagg_Paymentmodule_Helper_Chargeoperations $chargeOperations
                 */
                $chargeOperations = Mage::helper('paymentmodule/chargeoperations');
                /*$chargeOperations->updateChargeInfo(
                    $response->status,$response,
                    'Updated manually through the module. Value: ' . ($charge->amount / 100)
                );*/
                $method = $response->status . 'Methods';
                $chargeOperations->$method($response->status,$response,true);

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
        }catch(Throwable $e) {
            Mage::helper('paymentmodule/exception')->registerException($e);

            $response = new stdClass();
            $response->message = 'Internal Server error. Please contact support.';
            $response->status = 500;

            $this->getResponse()
                ->clearHeaders()
                ->setHeader('HTTP/1.0', 200, true)
                ->setHeader('Content-Type', 'application/json') // can be changed to json, xml...
                ->setBody(json_encode($response));
        }
    }
}
