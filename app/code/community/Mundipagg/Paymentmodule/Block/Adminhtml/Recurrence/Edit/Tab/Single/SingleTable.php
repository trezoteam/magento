<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Recurrence\Aggregates\Template\RepetitionValueObject;
use Mundipagg\Recurrence\Repositories\Decorators\MagentoPlatformDatabaseDecorator;
use Mundipagg\Recurrence\Repositories\TemplateRepository;

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tab_Single_SingleTable
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Initialize block
     */
    public function __construct()
    {
        $this->setTemplate('paymentmodule/singleTable.phtml');
    }

    /**
     * Prepare global layout
     * Add "Add cycle" button to layout
     *
     * @return Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tab_Single_SingleTable
     * @throws Varien_Exception
     */
    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => Mage::helper('catalog')->__('Add Cycle'),
                'onclick' => 'return tierPriceControl.addItem()',
                'class' => 'add',
            ));
        $button->setName('add_cycle_item_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }

    public function getIntervalTypesArray()
    {
        return RepetitionValueObject::getIntervalTypesArray();
    }

    public function getDiscountTypesArray()
    {
        return RepetitionValueObject::getDiscountTypesArray();
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    /**
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getEditData()
    {
        $data = Mage::registry('template_data');

        if (empty($data)) {
            return array();
        }

        $resource = Mage::getSingleton('core/resource');

        $templateRepository =
            new TemplateRepository(
                new MagentoPlatformDatabaseDecorator($resource)
            );

        $templateRoot = $templateRepository->find($data['id']);
        return $templateRoot->getRepetitions();
    }
}
