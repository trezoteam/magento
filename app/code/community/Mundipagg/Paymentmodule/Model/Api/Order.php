<?php
/**
 * This class is a Wrapper to the MundiPagg SDK
 *
 * @package Mundipagg/Paymentmodule
 */

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\MundiAPIClient;

class Mundipagg_Paymentmodule_Model_Api_Order
{
    public function createPayment(Varien_Object $paymentInformation)
    {
        $paymentMethod = $paymentInformation->getPaymentInfo()->getPaymentMethod();
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

    private function getOrderController()
    {
        $client = $this->getMundiPaggApiClient();

        return $client->getOrders();
    }

    private function getMundiPaggApiClient()
    {
        $generalConfig = Mage::getModel('paymentmodule/config_general');

        $secretKey = $generalConfig->getSecretKey();
        $password = $generalConfig->getPassword();

        return new MundiAPIClient($secretKey, $password);
    }
}
