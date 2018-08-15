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
        $magentoSystemInfo = \Mage::helper('paymentmodule/MagentoSystemInfo');

        if ($magentoSystemInfo->checkMaintenanceRouteAccessPermition()) {
            header('HTTP/1.0 401 Unauthorized');
            $this->getResponse()->setBody('Unauthorized');
            return;
        }

        $integrityController = new IntegrityController($magentoSystemInfo);

        $integrityCheck = $integrityController->getIntegrityCheck();
        $generalInformation = $integrityController->getSystemInformation();
        $generalInformation['moduleCheckSum'] = md5(json_encode($integrityCheck['files]']));

        //showing environment and module info
        $integrityController->showGeneralInfo("Module info", $generalInformation);

        //showing integrity check result
        $integrityController->showNonEmptyInfo(
            "Warning! New files were added to module directories!",
            $integrityCheck['newFiles']
        );

        $integrityController->showNonEmptyInfo(
            "Warning! Module files were modified!",
            $integrityCheck['alteredFiles']
        );

        $integrityController->showNonEmptyInfo(
            "Warning! Module files become unreadable!",
            $integrityCheck['unreadableFiles']
        );

        $integrityController->showGeneralInfo(
            'File List ('.count($integrityCheck['files']).')',
            $integrityCheck['files']
        );

    }

    public function logsAction()
    {
        $magentoSystemInfo = \Mage::helper('paymentmodule/MagentoSystemInfo');

        if ($magentoSystemInfo->checkMaintenanceRouteAccessPermition()) {
            header('HTTP/1.0 401 Unauthorized');
            $this->getResponse()->setBody('Unauthorized');
            return;
        }

        $integrityController = new IntegrityController($magentoSystemInfo);

        echo '<pre>';
        print_r($integrityController->getLogInfo());
    }
}
