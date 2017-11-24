<?php
/**
 * This class is a Wrapper to the MundiPagg SDK
 *
 * @package Mundipagg/Paymentmodule
 */

require_once Mage::getBaseDir('lib') . '/autoload.php';

require_once '/var/www/newmagento.localhost/public_html/.modman/magento/lib/mundipagg/mundiapi/src/Models/GetCustomerResponse.php';
require_once '/var/www/newmagento.localhost/public_html/.modman/magento/lib/mundipagg/mundiapi/src/Models/GetAddressResponse.php';
require_once '/var/www/newmagento.localhost/public_html/.modman/magento/lib/mundipagg/mundiapi/src/Models/GetPhonesResponse.php';
require_once '/var/www/newmagento.localhost/public_html/.modman/magento/lib/mundipagg/mundiapi/src/Models/GetPhoneResponse.php';
require_once '/var/www/newmagento.localhost/public_html/.modman/magento/lib/mundipagg/mundiapi/src/Models/GetTransactionResponse.php';

class GetCustomerResponse extends \MundiAPILib\Models\GetCustomerResponse{}
class GetAddressResponse extends \MundiAPILib\Models\GetAddressResponse{}
class GetPhonesResponse extends \MundiAPILib\Models\GetPhonesResponse{}
class GetPhoneResponse extends \MundiAPILib\Models\GetPhoneResponse{}
class GetTransactionResponse extends \MundiAPILib\Models\GetTransactionResponse{}

use MundiAPILib\MundiAPIClient;

class Mundipagg_Paymentmodule_Model_Api_Order
{
    public function createBoletoPayment(Varien_Object $paymentInformation)
    {
        $boleto = Mage::getModel('paymentmodule/api_boleto');
        $orderRequest = $boleto->getCreateOrderRequest($paymentInformation);
        $orderController = $this->getOrderController();

        return $orderController->createOrder($orderRequest);
    }

    public function createCreditCardPayment(Varien_Object $paymentInformation)
    {
        $creditCard = Mage::getModel('paymentmodule/api_creditcard');
        $orderRequest = $creditCard->getCreateOrderRequest($paymentInformation);

        $orderController = $this->getOrderController();
        return $orderController->createOrder($orderRequest);
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
