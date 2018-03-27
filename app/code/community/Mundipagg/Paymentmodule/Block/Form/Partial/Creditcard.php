<?php

class Mundipagg_Paymentmodule_Block_Form_Partial_Creditcard extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/form/partial/creditcard.phtml');
    }

    public function getPublicKey()
    {
        $generalConfig = Mage::getModel('paymentmodule/config_general');
        return $generalConfig->getPublicKey();
    }

    public function getSavedCreditCards()
    {
        $session = Mage::getSingleton('customer/session');
        $savedCreditCardsHelper = Mage::helper('paymentmodule/savedcreditcard');

        if (
            $session->isLoggedIn() &&
            $savedCreditCardsHelper->isSavedCreditCardsEnabled()
        ) {
            return $savedCreditCardsHelper->getCurrentCustomerSavedCards();
        }

        return [];
    }

    public function isSavedCreditCardsEnabled()
    {
        return Mage::helper('paymentmodule/savedcreditcard')
            ->isSavedCreditCardsEnabled();
    }
}
