<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tab_Single extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getTestsData()){
            $data = Mage::getSingleton('adminhtml/session')->getTestsData();
            Mage::getSingleton('adminhtml/session')->setTestsData(null);
        }elseif(Mage::registry('tests_data'))
            $data = Mage::registry('tests_data')->getData();

        $fieldset = $form->addFieldset('tests_form', array('legend'=> 'Single information'));

        $fieldset->addField('title', 'text', array(
            'label'     => 'Title',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }

}