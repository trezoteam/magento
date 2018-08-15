<?php

use Mundipagg\Integrity\SystemInfoInterface;

class Mundipagg_Paymentmodule_Helper_MagentoSystemInfo implements SystemInfoInterface
{
    public function getModuleVersion()
    {
        $data = \Mage::helper('paymentmodule')->getMetaData();
        return $data['module_version'];
    }

    public function getPlatformVersion()
    {
        $data = \Mage::helper('paymentmodule')->getMetaData();
        return 'magento ' .  $data['magento_version'];
    }

    public function getPlatformRootDir()
    {
        return \Mage::getBaseDir();
    }

    public function getDirectoriesIgnored()
    {
        return [
            "./lib/",
            "./var/connect/"
        ];
    }

    public function getModmanPath()
    {
        return '/app/app/code/community/Mundipagg/Paymentmodule/etc/maintenance/modman';
    }

    public function getIntegrityCheckPath()
    {
        return '/app/app/code/community/Mundipagg/Paymentmodule/etc/maintenance/integrityCheck';
    }

    public function getInstallType()
    {
        $installType = 'package';
        if (is_dir('./.modman')) {
            $installType = 'modman';
        }

        return $installType;
    }

    public function getLogsDir()
    {
        return \Mage::getBaseDir('log');
    }

    public function getDefaultLogFiles()
    {
        return [
            \Mage::getStoreConfig('dev/log/file'),
            \Mage::getStoreConfig('dev/log/exception_file'),
        ];
    }

    public function getModulePrefixLogFile()
    {
        return \Mage::helper('paymentmodule/log')->getModuleLogFilenamePrefix();
    }

    public function checkMaintenanceRouteAccessPermition()
    {
        $generalConfig = \Mage::getModel('paymentmodule/config_general');
        $publicKey = $generalConfig->getPublicKey();
        $publicKeyHashEncoded = base64_encode(hash('sha512',$publicKey));
        $urlToken = \Mage::app()->getRequest()->getParam('token');

        return $urlToken !== $publicKeyHashEncoded || strlen($publicKey) < 1;
    }
}
