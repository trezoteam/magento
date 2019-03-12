<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Magento\Concrete\MagentoModuleCoreSetup as MPSetup;

class Mundipagg_Paymentmodule_Block_Form_Partial_Creditcard extends Mundipagg_Paymentmodule_Block_Base
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentmodule/form/partial/creditcard.phtml');
    }

    public function getPublicKey()
    {
        $moduleConfig = MPSetup::getModuleConfiguration();
        return $moduleConfig->getPublicKey()->getValue();
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
                $savedCards = $savedCreditCardsHelper->enabledSavedCreditCards();
                return $this->filterAllowedSavedCreditCardBrands($savedCards);
            }

            return $savedCreditCardsHelper->getCurrentCustomerSavedCards();
        }

        return array();
    }

    private function filterAllowedSavedCreditCardBrands($savedCards)
    {
        $enabledBrands = $this->getEnabledBrands();
        $customerSavedCreditCards = array();

        foreach ($savedCards as $card) {
            if (in_array(strtolower($card->getBrandName()), $enabledBrands)) {
                $customerSavedCreditCards[] = $card;
            }
        }

        return $customerSavedCreditCards;
    }

    public function isSavedCreditCardsEnabled()
    {
        return Mage::helper('paymentmodule/savedcreditcard')
            ->isSavedCreditCardsEnabled();
    }

    public function getEnabledBrands()
    {
        return Mage::getModel('paymentmodule/config_card')
            ->getEnabledBrands();
    }
}
