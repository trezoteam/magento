<?php

namespace MundipaggModuleBackend\Hub\Commands;

use Exception;
use MundipaggModuleBackend\Core\AbstractMundipaggModuleCoreSetup as MPSetup;
use MundipaggModuleBackend\Core\Kernel\GatewayId\GUID;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\PublicKey;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\SecretKey;
use MundipaggModuleBackend\Core\Repositories\Configuration as ConfigurationRepository;

class UninstallCommand extends AbstractCommand
{
    public function execute()
    {
        $moduleConfig = MPSetup::getModuleConfiguration();

        if (!$moduleConfig->isHubEnabled()) {
            throw new Exception("Hub is not installed!");
        }

        $hubKey = $moduleConfig->getSecretKey();
        if (!$hubKey->equals($this->getAccessToken())) {
            throw new Exception("Access Denied.");
        }

        $moduleConfig->setHubInstallId(
            new GUID(null)
        );

        $moduleConfig->setPublicKey(
            new PublicKey(null)
        );
        $moduleConfig->setSecretKey(
            new SecretKey(null)
        );

        $moduleConfig->setTestMode(null);

        $configRepo = new ConfigurationRepository(
            MPSetup::getDatabaseAccessDecorator()
        );

        $configRepo->save($moduleConfig);
    }
}