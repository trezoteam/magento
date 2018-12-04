<?php

use Mundipagg\Core\AbstractMundipaggModuleCoreSetup as ConfigSingleton;

use Mundipagg_Paymentmodule_Model_Boleto as MagentoPlatformCartDecorator;
use Mundipagg_Paymentmodule_Model_MagentoPlatformDatabaseDecorator as MagentoPlatformDatabaseDecorator;
use Mundipagg_Paymentmodule_Model_Boleto as MagentoPlatformProductDecorator;
use Mundipagg_Paymentmodule_Model_Boleto as MagentoPlatformFormatService;

final class Mundipagg_Paymentmodule_Model_MagentoModuleCoreSetup extends ConfigSingleton
{
    static protected function setConfig()
    {
        self::$config = [
            ConfigSingleton::CONCRETE_CART_DECORATOR_CLASS => MagentoPlatformCartDecorator::class,
            ConfigSingleton::CONCRETE_DATABASE_DECORATOR_CLASS => MagentoPlatformDatabaseDecorator::class,
            ConfigSingleton::CONCRETE_PRODUCT_DECORATOR_CLASS => MagentoPlatformProductDecorator::class,

            ConfigSingleton::CONCRETE_FORMAT_SERVICE => MagentoPlatformFormatService::class
        ];
    }

    static public function getDatabaseAccessObject()
    {
        return Mage::getSingleton('core/resource');
    }

    static protected function getPlatformHubAppPublicAppKey()
    {
        return "magento";
    }
}