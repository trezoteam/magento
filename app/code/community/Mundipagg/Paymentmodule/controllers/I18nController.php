<?php

class Mundipagg_Paymentmodule_I18nController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }

    public function getTableAction()
    {
        $translateTable = Mage::app()->getTranslator()->getData();

        //ignore lines starting with # in the translate.csv file
        $translateTable = array_filter($translateTable,function($line) {
            return $line[0] !== '#';
        });

        $translateTable = (object) $translateTable;

        return $this->getResponse()
            ->clearHeaders()
            ->setHeader('HTTP/1.0', 200 , true)
            ->setHeader('Content-Type', 'text/html')
            ->setBody(json_encode($translateTable));
    }
}
