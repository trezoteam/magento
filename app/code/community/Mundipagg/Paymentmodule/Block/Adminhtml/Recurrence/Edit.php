<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('paymentmodule/recurrencetemplate.phtml');
    }
}