<?php


class Mundipagg_Paymentmodule_WalletController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $savedCreditCardsHelper = Mage::helper('paymentmodule/savedcreditcard');

        if (
            !$this->isUserLoggedIn() ||
            !$savedCreditCardsHelper->isSavedCreditCardsEnabled()
        ) {
            $this->_redirect('customer/account/', ['_secure' => true]);
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function deleteAction()
    {
        $savedCreditCardsHelper = Mage::helper('paymentmodule/savedcreditcard');

        if (
            $this->isUserLoggedIn() &&
            $savedCreditCardsHelper->isSavedCreditCardsEnabled()
        ) {
            $savedCreditCards = $savedCreditCardsHelper->getCurrentCustomerSavedCards();

            $deleteCardId = $this->getRequest()->getParam('cardId');
            if ($deleteCardId !== null) {
                $deleteCardId = intval($deleteCardId);
            }

            foreach ($savedCreditCards as $savedCreditCard) {
                if (intval($savedCreditCard['id']) === $deleteCardId) {
                    $savedCreditCardsHelper
                        ->deleteByMundipaggCardId(
                            $savedCreditCard['mundipagg_card_id']
                        );
                    break;
                }
            }
        }

        $this->_redirect('mundipagg/wallet/', ['_secure' => true]);
    }

    private function isUserLoggedIn()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return false;
        }
        return true;
    }
}