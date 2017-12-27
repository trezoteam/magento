<?php
class Mundipagg_Paymentmodule_CreditcardController extends Mundipagg_Paymentmodule_Controller_Payment
{
    /**
     * Gather credit card card transaction information and try to create
     * a payment using the sdk api wrapper.
     */
    public function processPaymentAction()
    {
        $apiOrder = Mage::getModel('paymentmodule/api_order');

        $paymentInfo = new Varien_Object();

        $paymentInfo->setItemsInfo($this->getItemsInformation());
        $paymentInfo->setCustomerInfo($this->getCustomerInformation());
        $paymentInfo->setPaymentInfo($this->getPaymentInformation());
        $paymentInfo->setMetaInfo(Mage::helper('paymentmodule/data')->getMetaData());

        $response = $apiOrder->createCreditcardPayment($paymentInfo);
        $this->handleOrderResponse($response,true);
    }


    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    private function getPaymentInformation()
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $creditCardConfig = Mage::getModel('paymentmodule/config_card');

        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();

        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId);

        $payment = new Varien_Object();

        // @todo get this from front end
        $payment->setInstallmentNumber('1');
        $payment->setPaymentMethod('credit_card');
        $payment->setInvoiceName($creditCardConfig->getInvoiceName());
        $payment->setOperationType($creditCardConfig->getOperationTypeFlag());
        $payment->setPaymentToken($additionalInformation['mundipagg_payment_module_token']);
        $payment->setHolderName($additionalInformation['mundipagg_payment_module_holder_name']);
        // @todo get this from store config
        $payment->setCurrency('BRL');

        return $payment;
    }

    /**
     * Only one credit card brand allowed
     */
    public function getInstallmentsAction()
    {
        $brandName[] = key(Mage::app()->getRequest()->getParams());
        $installmentConfig = Mage::helper('paymentmodule/installment');
        $grandTotal = Mage::getModel('checkout/session')->getQuote()->getGrandTotal();

        if (!empty($brandName[0])) {
            $installments =
                current(
                    $installmentConfig->getInstallments(
                        $grandTotal,
                        $brandName
                    )
                );
            echo json_encode($installments);
        } else {
            echo "";
        }
    }
}
