<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_TemplateTypeColumn
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $type = $row->getData('is_single');
        if ($type) {
            return '<span>Single</span>';
        }
        return '<span>Plan</span>';
    }
}