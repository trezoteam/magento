<?php

class Mundipagg_Paymentmodule_Model_Observer extends Varien_Event_Observer
{
    public function addAccountCreditcardWalletMenuItem(Varien_Event_Observer $observer)
    {
        $savedCreditCardsHelper = Mage::helper('paymentmodule/savedcreditcard');

        if ($savedCreditCardsHelper->isSavedCreditCardsEnabled()) {
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('creditcard_wallet_menu_item_handle');
        }
    }

    public function addAdditionalInformationToCheckout()
    {
        $block = Mage::app()->getLayout()->createBlock(
            'Mundipagg_Paymentmodule_Block_Checkout_Information'
        );

        $checkoutSuccessBlock =
            Mage::app()->getLayout()->getBlock('checkout.success');

        if ($checkoutSuccessBlock) {
            $checkoutSuccessBlock->append($block);
        }
    }

    public function injectTabs($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($this->isOrderPageView($block)) {
            $blockName = 'paymentmodule/adminhtml_order_charge';
            $block->addTabAfter(
                'order_charges',
                [
                    'label'     => Mage::helper('paymentmodule')->__('Cobranças'),
                    'title'     => Mage::helper('paymentmodule')->__('Cobranças'),
                    'content'   => Mage::app()
                                    ->getLayout()
                                    ->createBlock($blockName)
                                    ->toHtml(),
                ],
                'order_transactions'
            );
        }
    }

    public function setActiveTab($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($this->isOrderPageView($block)) {
            $block->setActiveTab('order_info');
        }
    }

    protected function isOrderPageView($block)
    {
        return $block instanceof Mage_Adminhtml_Block_Sales_Order_View_Tabs
            && $this->_getRequest()->getActionName() == 'view';
    }

    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

}
