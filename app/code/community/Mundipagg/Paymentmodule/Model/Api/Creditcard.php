<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use MundiAPILib\Models\CreateOrderRequest;
use MundiAPILib\Models\CreateCustomerRequest;
use MundiAPILib\Models\CreateAddressRequest;
use MundiAPILib\Models\CreatePhonesRequest;
use MundiAPILib\Models\CreatePhoneRequest;
use MundiAPILib\Models\CreatePaymentRequest;
use MundiAPILib\Models\CreateCreditCardPaymentRequest;

class Mundipagg_Paymentmodule_Model_Api_Creditcard extends Mundipagg_Paymentmodule_Model_Api_Standard
{
    public function getPayment($paymentData)
    {
        // do something
    }

//    public function getPayments($paymentInfo)
//    {
//        $paymentRequest = new CreatePaymentRequest();
//
//        $creditCardPaymentRequest = new CreateCreditCardPaymentRequest();
//        $creditCardPaymentRequest->installments = $paymentInfo->getInstallmentNumber();
//        $creditCardPaymentRequest->statementDescriptor = $paymentInfo->getInvoiceName();
//        $creditCardPaymentRequest->cardToken = $paymentInfo->getPaymentToken();
//        $creditCardPaymentRequest->capture = $paymentInfo->getOperationType();
//
//        $paymentRequest->paymentMethod = 'credit_card';
//        $paymentRequest->currency = $paymentInfo->getCurrency();
//        $paymentRequest->creditCard = $creditCardPaymentRequest;
//        /*
//         * $paymentInfo->getBaseGrandTotal() and $paymentInfo->getInterest() returns arrays
//         * for the future implementation of more than one creditcard payment methods.
//         */
//        $paymentRequest->amount = $paymentInfo->getBaseGrandTotal();
//        $paymentRequest->amount = $paymentRequest->amount[0];
//        //add interest
//        $interest =  $paymentInfo->getInterest();
//        $interest = $interest[0];
//        $paymentRequest->amount += $interest;
//
//        return 0($paymentRequest);
//    }
}