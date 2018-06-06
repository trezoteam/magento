<?php
/**
 * This class is a Wrapper to the MundiPagg SDK
 *
 * @package Mundipagg/Paymentmodule
 */

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateCancelChargeRequest;
use MundiAPILib\Models\CreateCaptureChargeRequest;
use MundiAPILib\MundiAPIClient;

class Mundipagg_Paymentmodule_Model_Api_Order
{
    /**
     * @param Varien_Object $paymentInformation
     * @return mixed|string
     */
    public function createPayment(Varien_Object $paymentInformation)
    {
        $paymentMethod = $paymentInformation->getPaymentInfo();
        $paymentMethod = str_replace("_",'',$paymentMethod);
        $paymentModel = Mage::getModel('paymentmodule/api_' . $paymentMethod);
        $orderRequest = $paymentModel->getCreateOrderRequest($paymentInformation);
        $orderController = $this->getOrderController();

        $helperLog = Mage::helper('paymentmodule/log');
        $helperLog->info("Request");
        $helperLog->info(json_encode($orderRequest,JSON_PRETTY_PRINT));

        try {
            $response = $orderController->createOrder($orderRequest);

            $helperLog->info("Response");
            $helperLog->info(json_encode($response,JSON_PRETTY_PRINT));
            return $response;
        } catch (\Exception $e) {
            $helperLog->error("Exception: " . $e->getMessage());
            $helperLog->error(json_encode($e->errors,JSON_PRETTY_PRINT));
            return $e->getMessage();
        }
    }


    public function captureCharge($chargeData) {
        return $this->updateCharge($chargeData, new CreateCaptureChargeRequest());
    }

    public function cancelCharge($chargeData) {
        return $this->updateCharge($chargeData, new CreateCancelChargeRequest());
    }

    protected function updateCharge($chargeData,$chargeRequest) {
        $method = 'captureCharge';
        if ($chargeRequest instanceof CreateCancelChargeRequest) {
            $method = 'cancelCharge';
        }

        $chargeRequest->amount = $chargeData->amount;

        $chargeController = $this->getChargeController();

        $helperLog = Mage::helper('paymentmodule/log');
        $helperLog->info("Request MANUAL CHARGE UPDATE: " . $method);
        $helperLog->info(json_encode($chargeData,JSON_PRETTY_PRINT));
        $helperLog->info(json_encode($chargeRequest,JSON_PRETTY_PRINT));
        try {
            $response = $chargeController->$method($chargeData->id,$chargeRequest);

            $helperLog->info("Response MANUAL CHARGE UPDATE: " . $method);
            $helperLog->info(json_encode($response,JSON_PRETTY_PRINT));
            return $response;
        } catch (\Exception $e) {
            $helperLog->error("Exception: " . $e->getMessage());
            $helperLog->error(json_encode($e->errors,JSON_PRETTY_PRINT));
            return $e->getMessage();
        }
    }

    protected function getOrderController()
    {
        $client = $this->getMundiPaggApiClient();

        return $client->getOrders();
    }

    protected function getChargeController() {
        $client = $this->getMundiPaggApiClient();

        return $client->getCharges();
    }

    protected function getMundiPaggApiClient()
    {
        $generalConfig = Mage::getModel('paymentmodule/config_general');

        $secretKey = $generalConfig->getSecretKey();
        $password = $generalConfig->getPassword();

        return new MundiAPIClient($secretKey, $password);
    }


}
