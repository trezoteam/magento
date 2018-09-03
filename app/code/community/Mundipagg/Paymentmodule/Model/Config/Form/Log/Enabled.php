<?php

class Mundipagg_Paymentmodule_Model_Config_Form_Log_Enabled extends Mage_Core_Model_Config_Data
{
    const DISABLED = 0;
    const ENABLED  = 1;

    public function save()
    {
        $isModuleLogsEnabled = $this->getValue();
        $magentoLogsEnabledConfigPath = 'dev/log/active';
        $isMagentoLogsEnabled = Mage::getStoreConfig($magentoLogsEnabledConfigPath);

        if ($isModuleLogsEnabled == self::ENABLED && $isMagentoLogsEnabled == self::DISABLED) {
            try {
                Mage::getConfig()->saveConfig($magentoLogsEnabledConfigPath, self::ENABLED);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return parent::save();
    }
}