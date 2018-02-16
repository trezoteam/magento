<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateBoletoPaymentRequest;
use MundiAPILib\Models\CreateOrderItemRequest;

class Mundipagg_Paymentmodule_Model_Api_Boletocc extends Mundipagg_Paymentmodule_Model_Api_Standard
{
    public function getPayments($paymentInfo)
    {
        $boletoApiModel = Mage::getModel('paymentmodule/api_boleto');
        $creditcardApiModel = Mage::getModel('paymentmodule/api_creditcard');

        $boletoPayments = $boletoApiModel->getPayments($paymentInfo);
        $creditcardPayments = $creditcardApiModel->getPayments($paymentInfo);

        $boletoPayments[0]->amount = $paymentInfo->getBoletoValue();
        $creditcardPayments[0]->amount = $paymentInfo->getCreditcardValue();
       return array_merge($boletoPayments,$creditcardPayments);
    }
}
