<?php

class Mundipagg_Paymentmodule_Adminhtml_ChargeController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/sales');
        $this->_addContent($this->getLayout()->createBlock('paymentmodule/adminhtml_order_charge'));
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
