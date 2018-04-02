<?php

class Mundipagg_Paymentmodule_I18nController extends Mage_Core_Controller_Front_Action
{
    public function getTableAction()
    {
        $translateTable = Mage::app()->getTranslator()->getData();

        //ignore lines starting with #
        $translateTable = array_filter($translateTable,function($line) {
            return $line[0] !== '#';
        });

        $translateTable = (object) $translateTable;
        echo json_encode($translateTable);
    }
}
