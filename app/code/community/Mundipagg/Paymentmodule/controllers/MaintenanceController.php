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
        $logInfo = $integrityController->getLogInfo();
        $generalInformation = $integrityController->getSystemInformation();
        $generalInformation['moduleCheckSum'] = md5(json_encode($integrityCheck['files]']));
        $generalInformation['magentoLogsDirectory'] = $logInfo['magentoLogsDirectory'];
        $generalInformation['logConfigs'] = $logInfo['logConfigs'];

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

        echo '<h3>phpinfo()</h3>';
        phpinfo();

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

        $url = '/mp-paymentmodule/maintenance/donwloadLog';
        $url .= '?token=' . \Mage::app()->getRequest()->getParam('token');

        $integrityController->showLogInfo($url, $integrityController->listLogFiles());
    }

    public function donwloadLogAction()
    {
        $magentoSystemInfo = \Mage::helper('paymentmodule/MagentoSystemInfo');
        $integrityController = new IntegrityController($magentoSystemInfo);

        if ($magentoSystemInfo->checkMaintenanceRouteAccessPermition()) {
            header('HTTP/1.0 401 Unauthorized');
            $this->getResponse()->setBody('Unauthorized');
            return;
        }

        $file = \Mage::app()->getRequest()->getParam('file');
        if (!$file) {
            header('HTTP/1.0 404 Not Found');
            $this->getResponse()->setBody('Resource not found');
            return;
        }

        $file = base64_decode($file);

        if (!is_readable($file) || !in_array($file, $integrityController->listLogFiles())) {
            header('HTTP/1.0 403 Not Found');
            $this->getResponse()->setBody('Forbidden');
            return;
        }

        if (!$integrityController->compactFile($file)) {
            header('HTTP/1.0 500 Internal Server Error');
            $this->getResponse()->setBody('Zip encoding failure');
        }
    }
}
