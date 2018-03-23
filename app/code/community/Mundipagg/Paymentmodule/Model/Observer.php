<?php


class Mundipagg_Paymentmodule_Model_Observer extends Varien_Event_Observer
{

    public function addAdditionalInformationToCheckout() {
        $block = Mage::app()->getLayout()->createBlock(
            'Mundipagg_Paymentmodule_Block_Checkout_Information'
        );
        Mage::app()->getLayout()->getBlock('checkout.success')->append($block);
    }

}
