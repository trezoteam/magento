<?php

use Mundipagg\Recurrence\Aggregates\Template\DueValueObject;

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_DueDateColumn
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $type = $row->getData('due_type');

        if ($type == DueValueObject::TYPE_EXACT) {
            return 'Every day ' . $row->getData('due_value');
        }

        if ($type == DueValueObject::TYPE_POSTPAID) {
            return 'Pos Paid';
        }

        if ($type == DueValueObject::TYPE_PREPAID) {
            return 'Pre Paid';
        }
    }
}