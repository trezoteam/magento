<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct(){
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'paymentmodule';
        $this->_controller = 'adminhtml_recurrence';
        $this->_mode = 'edit';

        $this->_updateButton('save', 'label', 'Save Item');
        $this->_updateButton('delete', 'label', 'Delete Item');
    }

    public function getHeaderText(){
//        if(Mage::registry('tests_data') && Mage::registry('tests_data')->getId())
//            return Mage::helper('tests')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('tests_data')->getTitle()));
//        return Mage::helper('tests')->__('Add Item');
    }

}