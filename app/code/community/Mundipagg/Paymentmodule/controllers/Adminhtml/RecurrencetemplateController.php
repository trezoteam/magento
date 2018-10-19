<?php

class Mundipagg_Paymentmodule_Adminhtml_RecurrencetemplateController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('mundipagg/recurrencetemplate');
        $this->_addContent($this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_template'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('paymentmodule/adminhtml_order_charge_grid')->toHtml()
        );
    }
}
