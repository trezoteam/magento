<?php

class Mundipagg_Paymentmodule_Block_Form_Partial_Boleto extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/form/partial/boleto.phtml');
    }
}
