<?php

namespace MundipaggModuleBackend\Hub\Commands;

use Exception;
use MundipaggModuleBackend\Core\AbstractMundipaggModuleCoreSetup as MPSetup;
use MundipaggModuleBackend\Core\Repositories\Configuration as ConfigurationRepository;

class InstallCommand extends AbstractCommand
{
    public function execute()
    {
        $moduleConfig = MPSetup::getModuleConfiguration();

        if ($moduleConfig->isHubEnabled()) {
            throw new Exception("Hub already installed!");
        }

        $moduleConfig->setHubInstallId($this->getInstallId());

        $moduleConfig->setTestMode(
            $this->getType()->equals(CommandType::Sandbox())
        );

        $moduleConfig->setPublicKey(
            $this->getAccountPublicKey()->getValue()
        );

        $moduleConfig->setSecretKey(
            $this->getAccessToken()->getValue()
        );

        $configRepo = new ConfigurationRepository(
            MPSetup::getDatabaseAccessDecorator()
        );

        $configRepo->save($moduleConfig);
    }
}