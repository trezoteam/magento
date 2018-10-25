<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Recurrence\Controller\Templates;

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

    public function newAction()
    {
        $this->_title($this->__('Mundipagg'))
            ->_title($this->__('Recurrence templates'))
            ->_title($this->__('Edit'));

        /**
         * @todo remove saveAction from here
         */
        $this->saveAction();

        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        /**
         * @todo Recieve post data from frontend
         */
        //$postData = $this->getRequest()->getParams();
        $postData = [
            'allow_installment' => "1",
             'description' => "descricao",
             'expiry_date' => "9",
             'expiry_type' => "X",
             'installments' => "1,4,7",
             'intervals' => [
                 [
                     'cycles'=> '1',
                     'frequency' => '4',
                     'type' => 'M'
                 ]
             ],
             'name' => "template para plano 2",
             'payment_method' => [
                 0 => 'credit_card',
                 1 => 'boleto'
             ],
             'trial' => "12"
        ];

        $resource = Mage::getSingleton('core/resource');


        $templates = new Templates($resource);
        $templates->saveTemplate($postData);
    }
}
