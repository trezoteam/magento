<?php

namespace Mundipagg\Magento\Concrete;

use Mage;
use Mundipagg\Core\Kernel\Abstractions\AbstractModuleCoreSetup;
use Mundipagg\Core\Kernel\Factories\ConfigurationFactory;
use Mundipagg\Core\Kernel\Repositories\ConfigurationRepository;

use Mundipagg_Paymentmodule_Model_Boleto as MagentoPlatformCartDecorator;
use Mundipagg_Paymentmodule_Model_Boleto as MagentoPlatformProductDecorator;
use Mundipagg_Paymentmodule_Model_Boleto as MagentoPlatformFormatService;

final class MagentoModuleCoreSetup extends AbstractModuleCoreSetup
{
    static protected function setConfig()
    {
        self::$config = [
            AbstractModuleCoreSetup::CONCRETE_CART_DECORATOR_CLASS => MagentoPlatformCartDecorator::class,
            AbstractModuleCoreSetup::CONCRETE_DATABASE_DECORATOR_CLASS => MagentoPlatformDatabaseDecorator::class,
            AbstractModuleCoreSetup::CONCRETE_PRODUCT_DECORATOR_CLASS => MagentoPlatformProductDecorator::class,
            AbstractModuleCoreSetup::CONCRETE_FORMAT_SERVICE => MagentoPlatformFormatService::class,
            AbstractModuleCoreSetup::CONCRETE_PLATFORM_ORDER_DECORATOR_CLASS => MagentoOrderDecorator::class,
            AbstractModuleCoreSetup::CONCRETE_PLATFORM_INVOICE_DECORATOR_CLASS => MagentoInvoiceDecorator::class
        ];
    }

    static public function getDatabaseAccessObject()
    {
        return Mage::getSingleton('core/resource');
    }

    static protected function getPlatformHubAppPublicAppKey()
    {
        return "2d2db409-fed0-4bd8-ac1e-43eeff33458d";
    }

    protected static function loadModuleConfiguration()
    {
        $store = Mage::getSingleton('adminhtml/config_data')->getScopeId();
        if ($store === null) {
            $store = Mage::app()->getStore()->getId();
        }

        $configurationRepository = new ConfigurationRepository;

        $savedConfig = $configurationRepository->findByStore($store);
        if ($savedConfig !== null) {
            self::$moduleConfig = $savedConfig;
            return;
        }

        $configData = new \stdClass;
        $configData->boletoEnabled =
            Mage::getModel('paymentmodule/config_boleto')->isEnabled();
        $configData->creditCardEnabled =
            Mage::getModel('paymentmodule/config_card')->isEnabled();
        $configData->boletoCreditCardEnabled =
            Mage::getModel('paymentmodule/config_boletocc')->isEnabled();
        $configData->twoCreditCardsEnabled =
            Mage::getModel('paymentmodule/config_twocreditcards')->isEnabled();
        $configData->hubInstallId = null;
        $configData->storeId = $store;

        $configData->cardConfigs = [];//self::getCardConfigs($storeConfig);

        $configurationFactory = new ConfigurationFactory();
        $config = $configurationFactory->createFromJsonData(
            json_encode($configData)
        );

        self::$moduleConfig = $config;
    }


    public static function loadModuleConfigurationByStore($storeId)
    {
        $configurationRepository = new ConfigurationRepository;

        $savedConfig = $configurationRepository->findByStore($storeId);
        if ($savedConfig !== null) {
            return $savedConfig;
        }
    }

    protected static function setModuleVersion()
    {
        $data = \Mage::helper('paymentmodule')->getMetaData();
        self::$moduleVersion = $data['module_version'];
    }

    protected static function setLogPath()
    {
        self::$logPath = [
            \Mage::getBaseDir('log')
        ];
    }

    protected static function _getDashboardLanguage()
    {
        // TODO: Implement _getDashboardLanguage() method.
    }

    protected static function _getStoreLanguage()
    {
        // TODO: Implement _getStoreLanguage() method.
    }

    protected static function _formatToCurrency($price)
    {
        // TODO: Implement _formatToCurrency() method.
    }
}