<?php

/**
 *
 * @author     Darko Goleš <darko.goles@inchoo.net>
 */
class  Mundipagg_Paymentmodule_Block_Adminhtml_Order_Charge_Popup extends Mage_Adminhtml_Block_Widget_Form {

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/chargePopup.phtml');
    }
}