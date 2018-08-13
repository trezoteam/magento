<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Integrity\MagentoSystemInfo;
use Mundipagg\Integrity\IntegrityController;

class Mundipagg_Paymentmodule_MaintenanceController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();
        \Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }


    public function versionAction()
    {
        $integrityController = new IntegrityController(
            \Mage::helper('paymentmodule/MagentoSystemInfo')
        );

        echo '<pre>';
        print_r($integrityController->getSystemInformation());

        die();
    }
}
