<?php

class Mundipagg_Paymentmodule_Block_Form_Partial_Creditcard extends Mundipagg_Paymentmodule_Block_Base
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

    /**
     * Return saved cards for logged in customers
     * @param boolean $onlyEnabledCards Only enabled card brands in admin panel
     * @return array
     */
    public function getSavedCreditCards($onlyEnabledCards = true)
    {
        $session = Mage::getSingleton('customer/session');
        $savedCreditCardsHelper =
            Mage::helper('paymentmodule/savedcreditcard');

        if (
            $session->isLoggedIn() &&
            $savedCreditCardsHelper->isSavedCreditCardsEnabled()
        ) {
            if ($onlyEnabledCards) {
                return $savedCreditCardsHelper->enabledSavedCreditCards();
            }

            return $savedCreditCardsHelper->getCurrentCustomerSavedCards();
        }

        return [];
    }

    public function isSavedCreditCardsEnabled()
    {
        return Mage::helper('paymentmodule/savedcreditcard')
            ->isSavedCreditCardsEnabled();
    }

    public function getEnabledBrands() {
        return Mage::getModel('paymentmodule/config_card')
            ->getEnabledBrands();
    }
}
