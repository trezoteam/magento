<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'paymentmodule';
        $this->_controller = 'adminhtml_recurrence_template';
        $this->_headerText = Mage::helper('paymentmodule')
            ->__('Recurrence templates');

        parent::__construct();

        $recurrenceConfig = Mage::getModel('paymentmodule/config_recurrence');

        if (
            !$recurrenceConfig->isSingleEnabled() &&
            !$recurrenceConfig->isPlanEnabled()
        ) {
            $this->_removeButton('add');
        }
    }
}
