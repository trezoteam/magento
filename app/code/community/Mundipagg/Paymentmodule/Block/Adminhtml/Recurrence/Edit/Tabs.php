<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct(){
        parent::__construct();
        $this->setId('tests_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Recurrence Template');
    }

    protected function _beforeToHtml(){
        $this->addTab('single_section', array(
            'label'  => 'Single',
            'title'  => 'Single',
            'content'    => $this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_edit_tab_single')->toHtml(),
        ));

        $this->addTab('plan_section', array(
            'label'  => 'Plan',
            'title'  => 'Plan',
            'content'    => $this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_edit_tab_plan')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}