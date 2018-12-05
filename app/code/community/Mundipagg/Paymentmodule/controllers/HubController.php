<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use \MundipaggModuleBackend\Hub\Services\HubIntegrationService;

class Mundipagg_Paymentmodule_HubController
    extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
        Mundipagg_Paymentmodule_Model_MagentoModuleCoreSetup::bootstrap($this);
    }

    public function generateIntegrationTokenAction()
    {
        $installSeed = "moises";
        $hubIntegrationService = new HubIntegrationService();

        echo $hubIntegrationService->startHubIntegration($installSeed);
        return;
    }

    public function validateInstallAction()
    {

    }

    public function statusAction()
    {

    }

    public function commandAction()
    {

    }

}