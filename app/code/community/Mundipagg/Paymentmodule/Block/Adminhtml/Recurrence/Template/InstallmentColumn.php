<?php

use Mundipagg\Recurrence\Repositories\Decorators\MagentoPlatformDatabaseDecorator;
use Mundipagg\Recurrence\Repositories\TemplateRepository;

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_InstallmentColumn
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $resource = Mage::getSingleton('core/resource');

        $templateRepository =
            new TemplateRepository(
                new MagentoPlatformDatabaseDecorator($resource)
            );

        $templateRoot = $templateRepository->find($row->getData('id'));

        $allow = "Yes";
        if (!$templateRoot->getTemplate()->isAllowInstallments()) {
            $allow = "No";
        }

        return $allow;
    }
}