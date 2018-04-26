<?php

class Mundipagg_Paymentmodule_Block_Form_Partial_Multibuyer extends Mundipagg_Paymentmodule_Block_Base
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/form/partial/multibuyer.phtml');
    }
}
