<?php

class Mundipagg_Paymentmodule_Block_Info_Boleto extends Mundipagg_Paymentmodule_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/info/boleto.phtml');
    }

    public function getPrintUrl()
    {
        $paymentInformation = $this->getInfo()->getAdditionalInformation($this->getMethod()->getCode());
        $billetData = array();

        if (isset($paymentInformation['boleto'])) {
            $billetData = reset($paymentInformation['boleto']);
        }

        return $billetData;
    }
}