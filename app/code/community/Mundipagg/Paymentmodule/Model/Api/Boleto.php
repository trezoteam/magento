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

class Mundipagg_Paymentmodule_Model_Api_Boleto extends Mundipagg_Paymentmodule_Model_Api_Standard
{
   public function getPayments($paymentInfo)
    {
        $paymentRequest = new CreatePaymentRequest();

        $boletoPaymentRequest = new CreateBoletoPaymentRequest();
        $boletoPaymentRequest->bank = $paymentInfo->getBank();
        $boletoPaymentRequest->instructions = $paymentInfo->getInstructions();
        $boletoPaymentRequest->dueAt = $paymentInfo->getDueAt();

        $paymentRequest->paymentMethod = 'boleto';
        $paymentRequest->boleto = $boletoPaymentRequest;
        // @todo this should not be hard coded
        $paymentRequest->currency = 'BRL';

        return [$paymentRequest];
    }
}
