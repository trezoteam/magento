<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('recurrence_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Recurrence Template');
    }

    protected function _beforeToHtml()
    {
        $tab = $this->getRequest()->getParam('tab');
        $this->renderTabs(strtolower($tab));
        return parent::_beforeToHtml();
    }

    protected function renderTabs($tab = null)
    {
        /** @todo Verify if the configuration of templates is enabled */
        if (!$this->isValidTab($tab)) {
            return $this->both();
        }
        return $this->$tab();
    }

    public function both()
    {
        $this->plan();
        $this->single();
    }

    public function plan()
    {
        return $this->addTab('plan_section', array(
            'label'  => 'Plan',
            'title'  => 'Plan',
            'content'    => $this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_edit_tab_plan')->toHtml(),
        ));
    }

    public function single()
    {
        return $this->addTab('single_section', array(
            'label'  => 'Single',
            'title'  => 'Single',
            'content'    => $this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_edit_tab_single')->toHtml(),
        ));
    }

    public function isValidTab($tab)
    {
        $validTabs = ['single', 'plan'];

        if (!in_array($tab, $validTabs) || is_null($tab)) {
            return false;
        }

        return true;
    }
}