<?php

class Mundipagg_Paymentmodule_Model_Creditcard extends Mundipagg_Paymentmodule_Model_Standard
{
    protected $_code = 'paymentmodule_creditcard';
    protected $_formBlockType = 'paymentmodule/form_creditcard';
    protected $_isGateway = true;
    protected $_canOrder  = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canVoid = true;
    protected $_canUseInternal = true;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = true;
    protected $_canSaveCc = false;
    protected $_canFetchTransactionInfo = false;
    protected $_canManageRecurringProfiles = false;
    protected $_allowCurrencyCode = array('BRL', 'USD', 'EUR');
    protected $_isInitializeNeeded = true;

    public function isAvailable($quote = null)
    {
        return true;
    }

    public function assignData($data)
    {
        parent::assignData($data);

        $key = $this->getBaseKey();
        $info = $this->getInfoInstance();
        $paymentData = $data->getData();

        $info->setAdditionalInformation($key . 'method', $paymentData['method']);
        $info->setAdditionalInformation($key . 'holder_name', $paymentData['holderName']);
        $info->setAdditionalInformation($key . 'token', $paymentData['creditCardToken']);

        return $this;
    }

    private function getBaseKey()
    {
        return 'mundipagg_payment_module_';
    }
}
