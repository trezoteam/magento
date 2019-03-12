<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Magento\Concrete\MagentoModuleCoreSetup as MPSetup;

class Mundipagg_Paymentmodule_Block_Adminhtml_HubIntegration
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        MPSetup::bootstrap();

        $moduleConfig = MPSetup::getModuleConfiguration();
        $hubPublicAppKey = MPSetup::getHubAppPublicAppKey();

        $locale = strtolower(
            str_replace("_", "-", Mage::app()->getLocale()->getLocaleCode())
        );

        $initHubScript = "
            initHub(
                '$hubPublicAppKey',
                '$locale'
        ";

        if ($moduleConfig->isHubEnabled()) {
            $initHubScript .= ",'". $moduleConfig->getHubInstallId()->getValue() . "'";
        }

        $initHubScript .= ');';

        return '
                <div id="hub-integation-button-container">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <span id="mundipagg-hub"></span>
                        </div>
                    </div>
                </div>
            <style>
                #mundipagg-hub button:hover, button:active {
                    background: #178176;
                }
                #mundipagg-hub button {
                    background: #00b7b4
                }
            </style>
            <script>
                document.addEventListener("DOMContentLoaded", function(event) {
                  ' . $initHubScript . '
                });
                document.querySelector("#mundipagg_config_general_group_hub_integration").value = 1
            </script>
        ';
    }
}