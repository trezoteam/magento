<?php

namespace MundipaggModuleBackend\Hub\Factories;

use MundipaggModuleBackend\Core\Kernel\GatewayId\AccountId;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\HubAccessTokenKey;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\PublicKey;
use MundipaggModuleBackend\Core\Kernel\GatewayKey\TestPublicKey;
use MundipaggModuleBackend\Core\Kernel\GatewayId\GUID;
use MundipaggModuleBackend\Core\Kernel\GatewayId\MerchantId;
use MundipaggModuleBackend\Hub\Commands\AbstractCommand;
use MundipaggModuleBackend\Hub\Commands\CommandType;
use ReflectionClass;

class CommandFactory
{
    /**
     * @param $object
     * @return AbstractCommand
     * @throws \ReflectionException
     */
    public function createFromStdClass($object)
    {
        $commandClass = (new ReflectionClass(AbstractCommand::class))->getNamespaceName();
        $commandClass .= "\\" . $object->command . "Command";

        if (!class_exists($commandClass)) {
            throw new \Exception("Invalid Command class! $commandClass");
        }

        /** @var AbstractCommand $command */
        $command = new $commandClass();

        $command->setAccessToken(
            new HubAccessTokenKey($object->accessToken)
        );
        $command->setAccountId(
            new AccountId($object->accountId)
        );

        $type = $object->type;
        $command->setType(
            CommandType::$type()
        );

        $publicKeyClass = PublicKey::class;
        if ($command->getType()->equals(CommandType::Sandbox())) {
            $publicKeyClass = TestPublicKey::class;
        }

        $command->setAccountPublicKey(
            new $publicKeyClass($object->accountPublicKey)
        );

        $command->setInstallId(
            new GUID($object->installId)
        );

        $command->setMerchantId(
            new MerchantId($object->merchantId)
        );

        return $command;
    }
}