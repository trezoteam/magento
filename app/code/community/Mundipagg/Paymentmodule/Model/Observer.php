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
}
