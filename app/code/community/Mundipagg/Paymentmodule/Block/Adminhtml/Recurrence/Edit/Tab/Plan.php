<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tab_Plan extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getTestsData()){
            $data = Mage::getSingleton('adminhtml/session')->getTestsData();
            Mage::getSingleton('adminhtml/session')->setTestsData(null);
        }elseif(Mage::registry('tests_data'))
            $data = Mage::registry('tests_data')->getData();

        $fieldset = $form->addFieldset('tests_form', array('legend'=> 'Plan information'));

        $fieldset->addField('name', 'text', array(
            'label'     => 'Name',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));

        $fieldset->addField('description', 'textarea', array(
            'label'     => 'Description',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'description',
        ));

        $fieldset->addField('paymentmethods', 'checkboxes', array(
            'label'     => 'Payment Methods',
            'class'     => 'paymentmethods',
            'name'      => 'paymentmethods[]',
            'values'    => [
                ['value' => 'credit_card', 'label' => 'Credit Card'],
                ['value' => 'boleto', 'label' => 'Boleto']
            ],
            'onchange' => "", //abrir input de installments
        ));

        $fieldset->addField('duetype', 'select', array(
            'label'     => 'Due',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'duetype',
            'values'   => [
                ['value' => 'x', 'label' => 'Exact'],
                ['value' => 'e', 'label' => 'Pre Paid'],
                ['value' => 'o', 'label' => 'Post Paid']
            ]
        ));

        $fieldset->addField('duetvalue', 'select', array(
            'label'     => 'Day',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'duevalue',
            'values'   => [
                ['value' => 1, 'label' => 1],
                ['value' => 2, 'label' => 2],
                ['value' => 3, 'label' => 3],
                ['value' => 4, 'label' => 4],
            ]
        ));

        $fieldset->addField('cycle', 'text', array(
            'label'     => 'Cycles',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'cycle',
            'type'      => 'number'
        ));

        $fieldset->addField('interval', 'select', array(
            'label'     => 'Interval',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'interval',
            'values'   => [
                ['value' => 1, 'label' => 1],
                ['value' => 2, 'label' => 2],
                ['value' => 3, 'label' => 3],
                ['value' => 4, 'label' => 4],
                ['value' => 5, 'label' => 5],
                ['value' => 6, 'label' => 6],
                ['value' => 7, 'label' => 7],
                ['value' => 8, 'label' => 8],
                ['value' => 9, 'label' => 9],
                ['value' => 10, 'label' => 10],
                ['value' => 11, 'label' => 11],
                ['value' => 12, 'label' => 12]
            ]
        ));

        $fieldset->addField('intervaltype', 'select', array(
            'label'     => 'Interval Type',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'intervaltype',
            'values'   => [
                ['value' => 'w', 'label' => 'Weekly'],
                ['value' => 'm', 'label' => 'Monthly'],
                ['value' => 'y', 'label' => 'Yearly']
            ],
        ));

        $fieldset->addField('trial', 'text', array(
            'label'     => 'Trial',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'trial',
            'placeholder'      => 'Trial days'
        ));


        $form->setValues($data);
        return parent::_prepareForm();
    }

}