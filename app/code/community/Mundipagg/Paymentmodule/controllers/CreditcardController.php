<?php

use MundiAPILib\Models\GetOrderResponse;

class Mundipagg_Paymentmodule_CreditcardController extends Mundipagg_Paymentmodule_Controller_Payment
{
    /**
     * Only one credit card brand allowed
     */
    public function getInstallmentsAction()
    {
        $brandName[] = key(Mage::app()->getRequest()->getParams());
        $value = $this->getRequest()->getParam('value');
        $installmentConfig = Mage::helper('paymentmodule/installment');

        $grandTotal = floatval($value);
        if($value === null) {
            $grandTotal = Mage::getModel('checkout/session')
                ->getQuote()->getGrandTotal();
        }

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
