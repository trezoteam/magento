<?php

class Mundipagg_Paymentmodule_Model_Boletocc extends Mundipagg_Paymentmodule_Model_Standard
{
    protected $_code = 'paymentmodule_boletocc';
    protected $_formBlockType = 'paymentmodule/form_boletocc';
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
        $boletoCcConfig = Mage::getModel('paymentmodule/config_boletocc');
        return $boletoCcConfig->isEnabled();
    }

    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }

        parent::assignData($data);

        $key = $this->getBaseKey();
        $info = $this->getInfoInstance();
        $paymentData = $data->getData();
        $monetaryHelper = Mage::helper('paymentmodule/monetary');

        $boletoValue = $data->getMundipaggBoletoValueBoletocc();
        $creditcardValue = $data->getMundipaggCreditcardValueBoletocc();
        $boletoValue = floatval(str_replace(",",'.',$boletoValue));
        $creditcardValue = floatval(str_replace(",",'.',$creditcardValue));

        $baseGrandTotal =  $info->getQuote()->getBaseGrandTotal();
        if($boletoValue + $creditcardValue != $baseGrandTotal) {
            throw new Exception(
                "Payment values sum differs from baseGrandTotal."
            );
        }

        $interestHelper = Mage::helper("paymentmodule/interest");
        $interest = $interestHelper->getInterestValue(
            $paymentData['creditCardInstallments'],
            $creditcardValue,
            null,
            $data->getMundipaggCreditcardBrandNameBoletocc()
        );

        $info->setAdditionalInformation(
            $key . 'interest',
            $monetaryHelper->toCents($interest)
        );

        $info->setAdditionalInformation(
            $key . 'base_grand_total',
            $monetaryHelper->toCents($baseGrandTotal)
        );

        foreach ($info->getQuote()->getAllAddresses() as $address) {
            $address->setMundipaggInterest($interest);
            $address->setGrandTotal($address->getGrandTotal() + $interest);
            break;
        }

        $boletoValue = $monetaryHelper->toCents($boletoValue);
        $creditcardValue = $monetaryHelper->toCents($creditcardValue);

        $interest = $monetaryHelper->toCents($interest);
        // @todo possible code exception
        $info->setAdditionalInformation($key . 'boleto_value', $boletoValue);
        $info->setAdditionalInformation($key . 'creditcard_value', $creditcardValue + $interest);
        $info->setAdditionalInformation($key . 'method', $paymentData['method']);
        $info->setAdditionalInformation($key . 'holder_name', $paymentData['holderName']);
        $info->setAdditionalInformation($key . 'token', $paymentData['creditCardToken']);
        $info->setAdditionalInformation($key . 'installments', $paymentData['creditCardInstallments']);

        return $this;
    }

    private function getBaseKey()
    {
        return 'mundipagg_payment_module_';
    }
}