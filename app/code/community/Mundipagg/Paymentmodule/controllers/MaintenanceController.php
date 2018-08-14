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

        $integrityCheck = $integrityController->getIntegrityCheck();

        $generalInformation = $integrityController->getSystemInformation();
        $generalInformation['moduleCheckSum'] = md5(json_encode($integrityCheck['files]']));

        echo '<pre>';

        //showing environment and module info
        echo "<h3>Module info</h3>";
        echo '<pre>';
        print_r($generalInformation);
        echo '</pre>';
        echo json_encode($generalInformation);

        //showing integrity check result
        if (count($integrityCheck['newFiles']) > 0) {
            echo "<h3 style='color:red'>Warning! New files were added to module directories!</h3>";
            echo '<pre>';
            print_r($integrityCheck['newFiles']);
            echo '</pre>';
            echo json_encode($integrityCheck['newFiles']);
        }

        if (count($integrityCheck['alteredFiles']) > 0) {
            echo "<h3 style='color:red'>Warning! Module files were modified!</h3>";
            echo '<pre>';
            print_r($integrityCheck['alteredFiles']);
            echo '</pre>';
            echo json_encode($integrityCheck['alteredFiles']);
        }

        if (count($integrityCheck['unreadableFiles']) > 0) {
            echo "<h3 style='color:red'>Warning! Module files become unreadable!</h3>";
            echo '<pre>';
            print_r($integrityCheck['unreadableFiles']);
            echo '</pre>';
            echo json_encode($integrityCheck['unreadableFiles']);
        }

        echo '<h3>File List ('.count($integrityCheck['files']).')</h3><pre>';
        print_r($integrityCheck['files']);
        echo '</pre>';
        echo json_encode($integrityCheck['files']);

    }
}
