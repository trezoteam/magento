<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_HubIntegration
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
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
        ';
    }
}