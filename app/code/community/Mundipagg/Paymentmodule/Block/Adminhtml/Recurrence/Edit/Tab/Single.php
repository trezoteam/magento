<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit_Tab_Single extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm(){
        $form = new Varien_Data_Form(array(
                'id'        => 'single_form',
                'action'    => $this->getUrl('*/*/saveSingle', array(
                    'id'    => $this->getRequest()->getParam('id'),
                )),
                'method'    => 'post',
                'enctype'   => 'multipart/form-data'
            )
        );

        $fieldset = $form->addFieldset('fieldset_single_form', array('legend'=> 'Single information'));

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
            'name'      => 'payment_method[]',
            'values'    => [/** @todo improve */
                ['value' => 'credit_card', 'label' => 'Credit Card'],
                ['value' => 'boleto', 'label' => 'Boleto']
            ],
            'onchange' => "toogleInstallments(this)",
        ));

        $fieldset->addField('installments', 'text', array(
            'label'     => 'Installments',
            'name'      => 'installments',
            'class'      => 'installments',
        ));

        $fieldset->addField('expiry_type', 'select', array(
            'label'     => 'Due',
            'class'     => 'required-entry expiry_type',
            'required'  => true,
            'name'      => 'expiry_type',
            'values'   => [/** @todo improve */
                ['value' => 'X', 'label' => 'Exact'],
                ['value' => 'E', 'label' => 'Pre Paid'],
                ['value' => 'O', 'label' => 'Post Paid']
            ],
            'onchange' => "toogleExpiryType(this)"
        ));

        $fieldset->addField('expiry_date', 'select', array(
            'label'     => 'Day',
            'name'      => 'expiry_date',
            'class'     => 'expiry_date',
            'values'   =>/** @todo improve */
                array_map(function ($value) {
                    return ['value' => $value, 'label' => $value];
                }, range(1, 31)),
        ));

        $fieldset->addField('cycle', 'text', array(
            'label'     => 'Cycles',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'intervals[0][cycles]',
            'type'      => 'number'
        ));

        $fieldset->addField('frequency', 'select', array(
            'label'     => 'Interval',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'intervals[0][frequency]',
            'values'   =>/** @todo improve */
                array_map(function ($value) {
                    return ['value' => $value, 'label' => $value];
                }, range(1, 12))
        ));

        $fieldset->addField('type', 'select', array(
            'label'     => 'Interval Type',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'intervals[0][type]',
            'values'   => [/** @todo improve */
                ['value' => 'W', 'label' => 'Weekly'],
                ['value' => 'M', 'label' => 'Monthly'],
                ['value' => 'Y', 'label' => 'Yearly']
            ],
        ));

        $fieldset->addField('trial', 'text', array(
            'label'     => 'Trial',
            'name'      => 'trial',
            'placeholder'      => 'Trial days',
            'after_element_html' => '<button type="submit" style="margin-top: 10px;">Save</button>'
        ));


        if (Mage::registry('template_data')){
            $form->setValues(Mage::registry('template_data'));
        }

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}