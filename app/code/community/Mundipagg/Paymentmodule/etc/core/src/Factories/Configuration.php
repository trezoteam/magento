<?php

namespace Mundipagg\Core\Factories;

use Mundipagg\Core\Aggregates\Configuration as ConfigAggregate;
use Mundipagg\Core\Kernel\GatewayId\GUID;
use Mundipagg\Core\Kernel\Configuration\CardConfig;

class Configuration
{
    public function createEmpty()
    {
        return new ConfigAggregate();
    }

    public function createFromPostData($postData)
    {
        $config = new ConfigAggregate();

        foreach ($postData['creditCard'] as $brand => $cardConfig) {
            $config->addCardConfig(new CardConfig(
                $cardConfig['is_enabled'],
                $brand,
                $cardConfig['installments_up_to'],
                $cardConfig['installments_without_interest'],
                $cardConfig['interest'],
                $cardConfig['incremental_interest']
            ));
        }

        $config->setBoletoEnabled($postData['payment_mundipagg_boleto_status']);
        $config->setCreditCardEnabled($postData['payment_mundipagg_credit_card_status']);
        $config->setBoletoCreditCardEnabled($postData['payment_mundipagg_boletoCreditCard_status']);
        $config->setTwoCreditCardsEnabled($postData['payment_mundipagg_credit_card_two_credit_cards_enabled']);

        return $config;
    }

    public function createFromJsonData($json)
    {
        $config = new ConfigAggregate();
        $data = json_decode($json);

        foreach ($data->cardConfigs as $cardConfig) {
            $config->addCardConfig(new CardConfig(
                $cardConfig->enabled,
                $cardConfig->brand,
                $cardConfig->maxInstallment,
                $cardConfig->maxInstallmentWithoutInterest,
                $cardConfig->initialInterest,
                $cardConfig->incrementalInterest
            ));
        }
        $config->setBoletoEnabled($data->boletoEnabled);
        $config->setCreditCardEnabled($data->creditCardEnabled);
        $config->setBoletoCreditCardEnabled($data->boletoCreditCardEnabled);
        $config->setTwoCreditCardsEnabled($data->twoCreditCardsEnabled);

        $config->setTestMode($data->testMode);
        if ($data->hubInstallId !== null) {
            $config->setHubInstallId(
                new GUID($data->hubInstallId)
            );
        }

        $config->setPublicKey((array)$data->keys);
        $config->setSecretKey((array)$data->keys);

        return $config;
    }
}