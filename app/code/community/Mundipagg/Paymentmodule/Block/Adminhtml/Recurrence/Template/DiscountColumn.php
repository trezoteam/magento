<?php

use Mundipagg\Recurrence\Aggregates\Template\RepetitionValueObject;
use Mundipagg\Recurrence\Repositories\Decorators\MagentoPlatformDatabaseDecorator;
use Mundipagg\Recurrence\Repositories\TemplateRepository;

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_DiscountColumn
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

        if (!$templateRoot->getTemplate()->isSingle()) {
            return "";
        }

        $repetitions = $templateRoot->getRepetitions();

        $result = "";
        foreach ($repetitions as $repetition) {
            $result .= $repetition->getCycles() . " cycles ";
            $result .= $repetition->getFrequency() . " " . $repetition->getIntervalTypeLabel();
            $result .= $this->formatDiscounts($repetition);
            $result .= "<br />";
        }

        return $result;
    }

    public function formatDiscounts($repetition)
    {
        if ($repetition->getDiscountValue() <= 0) {
            return;
        }

        if ($repetition->getDiscountType() == RepetitionValueObject::DISCOUNT_TYPE_PERCENT) {
            return ": " . $repetition->getDiscountValue() ."%";
        }

        return ": " . Mage::helper('core')->currency($repetition->getDiscountValue(), true, false);
    }
}