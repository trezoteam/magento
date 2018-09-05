<?php

class Mundipagg_Paymentmodule_Block_Form_Partial_Voucher extends Mundipagg_Paymentmodule_Block_Base
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/form/partial/voucher.phtml');
    }
}
