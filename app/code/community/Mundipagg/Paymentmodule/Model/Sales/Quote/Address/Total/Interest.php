<?php
class Mundipagg_Paymentmodule_Model_Sales_Quote_Address_Total_Interest extends
    Mage_Sales_Model_Quote_Address_Total_Abstract{
    protected $_code = 'mundipagg_interest';
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $standard = Mage::getModel('paymentmodule/standard');
        $checkoutSession = $standard->getCheckoutSession();
        $orderId = $checkoutSession->getLastRealOrderId();
        /*$additionalInformation = $standard->getAdditionalInformationForOrder($orderId);
        $quote = $address->getQuote();
        $grandTotal = $quote->getGrandTotal();
        $interestHelper = Mage::helper('paymentmodule/interest');
        $installmentsNum = $additionalInformation['mundipagg_payment_module_installments'];
        $interest = $interestHelper->getInterestValue($installmentsNum, $grandTotal);*/
        $interest = 0;
        $this->_setAmount($interest);
        $this->_setBaseAmount($interest);
        $quote = $address->getQuote();
        //$balance = $fee - $exist_amount;
        $address->setFeeAmount(3);
        $address->setBaseFeeAmount(1);
        $quote->setFeeAmount(33);
        $address->setGrandTotal($address->getGrandTotal() + $address->getFeeAmount());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseFeeAmount());
    }
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amt = $address->getFeeAmount();
        $address->addTotal(array(
            'code'=>$this->getCode(),
            'title'=>"testano",
            'value'=> $amt
        ));
        return $this;
    }
}