<?php

namespace MundipaggModuleBackend\Hub\Services;

use MundipaggModuleBackend\Hub\Aggregates\InstallToken;
use MundipaggModuleBackend\Hub\Factories\CommandFactory as HubCommandFactory;
use MundipaggModuleBackend\Hub\Factories\InstallTokenFactory;
use MundipaggModuleBackend\Hub\Repositories\InstallTokenRepository;
use MundipaggModuleBackend\Core\AbstractMundipaggModuleCoreSetup as MPSetup;
use Unirest\Request;

final class HubIntegrationService
{
    public function startHubIntegration($installSeed)
    {
        $tokenRepo = new InstallTokenRepository();

        $enabledTokens = $tokenRepo->listEntities();

        //expire all tokens
        foreach ($enabledTokens as $enabledToken) {
            $enabledToken->setExpireAtTimestamp(
                $enabledToken->getCreatedAtTimestamp() - 1000
            );
            $tokenRepo->save($enabledToken);
        }

        $installFactory = new InstallTokenFactory();
        $installToken = $installFactory->createFromSeed($installSeed);

        $tokenRepo->save($installToken);

        return $installToken->getToken();
    }

    public function endHubIntegration(
        $installToken,
        $authorizationCode,
        $hubCallbackUrl = null,
        $webhookUrl = null
    )
    {
        $tokenRepo = new InstallTokenRepository();

        $installToken = $tokenRepo->findByToken($installToken);

        if (
            is_a($installToken, InstallToken::class) &&
            !$installToken->isExpired() &&
            !$installToken->isUsed()
        ) {
            $body = [
                "code" => $authorizationCode
            ];

            if ($hubCallbackUrl) {
                $body['hubCallbackUrl'] = $hubCallbackUrl;
            }

            if ($webhookUrl) {
                $body['webhookUrl'] = $webhookUrl;
            }

            $url = 'https://hubapi.mundipagg.com/auth/apps/access-tokens';
            $headers = [
                'PublicAppKey' => '5f826207-5e4e-42c2-be49-e69b8d4da233',
                'Content-Type' => 'application/json'
            ];

            $result = Request::post(
                $url,
                $headers,
                json_encode($body)
            );

            $this->executeCommandFromPost($result->body);

            //if its ok
            $installToken->setUsed(true);
            $tokenRepo->save($installToken);
        }
    }

    public function getHubStatus()
    {
        $moduleConfig = MPSetup::getModuleConfiguration();

        return $moduleConfig->isHubEnabled() ? 'enabled' : 'disabled';
    }

    public function executeCommandFromPost($body)
    {
        $commandFactory = new HubCommandFactory();
        $command = $commandFactory->createFromStdClass($body);
        $command->execute();
    }
}