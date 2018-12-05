<?php

namespace MundipaggModuleBackend\Core;

use Exception;
use MundipaggModuleBackend\Core\Aggregates\Configuration;
use MundipaggModuleBackend\Core\Repositories\Configuration as ConfigurationRepository;

abstract class AbstractMundipaggModuleCoreSetup
{
    const CONCRETE_MODULE_CORE_SETUP_CLASS = 0;

    const CONCRETE_CART_DECORATOR_CLASS = 10;
    const CONCRETE_DATABASE_DECORATOR_CLASS = 11;
    const CONCRETE_PRODUCT_DECORATOR_CLASS = 12;

    const CONCRETE_FORMAT_SERVICE = 1000;

    static protected $instance;
    static protected $config;
    static protected $platformRoot;
    /** @var Configuration */
    static protected $moduleConfig;

    protected function __construct() {}

    /**
     * @return mixed
     */
    static public function getPlatformRoot()
    {
        return static::$platformRoot;
    }

    /**
     * @param null $platformRoot
     * @throws Exception
     */
    static public function bootstrap($platformRoot = null)
    {
        if (static::$instance === null) {
            static::$instance = new static();
            static::$instance->setConfig();
            static::$config[self::CONCRETE_MODULE_CORE_SETUP_CLASS] = static::class;

            static::$platformRoot = $platformRoot;

            $DBDecoratorClass = static::get(self::CONCRETE_DATABASE_DECORATOR_CLASS);
            $configRepository = new ConfigurationRepository(
                new $DBDecoratorClass(static::getDatabaseAccessObject())
            );

            static::$moduleConfig = $configRepository->find(1);
        }
    }

    /**
     * @return Configuration
     */
    static public function getModuleConfiguration()
    {
        return static::$moduleConfig;
    }

    static public function get($configId)
    {
        self::bootstrap();

        if (!isset(static::$config[$configId])) {
            throw new Exception("Configuration $configId wasn't set!");
        }

        return static::$config[$configId];
    }

    static public function getAll()
    {
        self::bootstrap();

        return static::$config;
    }

    static public function getHubAppPublicAppKey()
    {
        $moduleCoreSetupClass = self::get(self::CONCRETE_MODULE_CORE_SETUP_CLASS);
        return $moduleCoreSetupClass::getPlatformHubAppPublicAppKey();
    }

    static public function getDatabaseAccessDecorator()
    {
        $concreteCoreSetupClass = self::get(self::CONCRETE_MODULE_CORE_SETUP_CLASS);
        $DBDecoratorClass = $concreteCoreSetupClass::get(self::CONCRETE_DATABASE_DECORATOR_CLASS);

        return new $DBDecoratorClass($concreteCoreSetupClass::getDatabaseAccessObject());
    }
    abstract static protected function setConfig();
    abstract static public function getDatabaseAccessObject();
    /** @return string **/
    abstract static protected function getPlatformHubAppPublicAppKey();
}