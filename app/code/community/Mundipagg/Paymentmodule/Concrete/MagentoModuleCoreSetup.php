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
        $configurationRepository = new ConfigurationRepository;

        $savedConfig = $configurationRepository->find(1);
        if ($savedConfig !== null) {
            self::$moduleConfig = $savedConfig;
            return;
        }

        $moduleConfig = self::getModuleConfigDataViaReflection();

        $configData = new \stdClass;
        $configData->boletoEnabled = $moduleConfig["boleto_status"] === '1';
        $configData->creditCardEnabled = $moduleConfig["credit_card_status"] === '1';
        $configData->boletoCreditCardEnabled = $moduleConfig["boletoCreditCard_status"] === '1';
        $configData->twoCreditCardsEnabled = $moduleConfig["credit_card_two_credit_cards_enabled"] === 'true';
        $configData->hubInstallId = null;

        $configData->cardConfigs = [];//self::getCardConfigs($storeConfig);

        $configurationFactory = new ConfigurationFactory();
        $config = $configurationFactory->createFromJsonData(
            json_encode($configData)
        );

        self::$moduleConfig = $config;
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

    private static function getModuleConfigDataViaReflection()
    {
        try {
            $config = self::$platformRoot->config;
            $configReflection = new \ReflectionClass(Config::class);

            $dataProperty = $configReflection->getProperty('data');
            $dataProperty->setAccessible(true);

            $data = $dataProperty->getValue($config);
            $dataProperty->setAccessible(true);
            $moduleConfig = [];

            foreach ($data as $key => $value)
            {
                if (strpos($key, 'payment_mundipagg') !== false) {
                    $moduleConfig[str_replace('payment_mundipagg_', '', $key)] = $value;
                }
            }

            return $moduleConfig;

        } catch (\Exception $e) {
            return [];
        }
    }

}