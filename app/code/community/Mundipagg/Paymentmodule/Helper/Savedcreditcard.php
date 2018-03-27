<?php

class Mundipagg_Paymentmodule_Helper_Savedcreditcard extends Mage_Core_Helper_Abstract
{
    /**
     * @param stdClass $response Order creation response
     * @throws Exception
     */
    public function saveCards($response)
    {
        if (empty($response->charges)) {
            throw new \Exception('Charge not found');
        }

        $session = Mage::getSingleton('customer/session');
        $customerLogged = $session->isLoggedIn();

        if(!$customerLogged) {
            return;
        }

        $customerId = $session->getCustomer()->getId();
        $standard = Mage::getModel('paymentmodule/standard');
        $orderId‌ = $response->code;

        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId‌);
        $paymentMethod = $additionalInformation['mundipagg_payment_method'];
        $cards = $additionalInformation[$paymentMethod]['creditcard'];

        foreach ($cards as $key => $card) {
            if (
                isset($card['saveCreditCard']) &&
                $card['saveCreditCard'] === 'on'
            ) {
                $this->save(
                    $response->charges[$key -1]->lastTransaction->card,
                    $response->charges[$key -1]->customer->id,
                    $customerId
                );
            }
        }
    }

    /**
     * @param stdClass $card
     * @param string $mundipaggCustomerId
     * @throws Exception
     */
    private function save($card, $mundipaggCustomerId, $customerId)
    {
        $saveCreditCard = Mage::getModel('paymentmodule/savedcreditcard');
        try {
            if(empty($saveCreditCard->loadByMundipaggCardId($card->id)->getData())) {
                $saveCreditCard->setMundipaggCardId($card->id);
                $saveCreditCard->setMundipaggCustomerId($mundipaggCustomerId);
                $saveCreditCard->setCustomerId($customerId);
                $saveCreditCard->setHolderName($card->holderName);
                $saveCreditCard->setBrandName($card->brand);
                //$saveCreditCard->setFirstSixDigits($card->firstSixDigits);
                $saveCreditCard->setLastFourDigits($card->lastFourDigits);
                $saveCreditCard
                    ->setExpirationDate(
                        $card->expYear .
                        "-" .
                        $card->expMonth .
                        "-" .
                        "01"
                    );
                $saveCreditCard->save();
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteByCardId($id)
    {
        $this->delete('id',$id);
    }

    public function deleteByMundipaggCardId($id)
    {
        $this->delete('mundipagg_card_id', $id);
    }

    private function delete ($field,$value)
    {
        $savedCreditCardModel = Mage::getModel('paymentmodule/savedcreditcard');
        $savedCreditCardCollection = $savedCreditCardModel->getResourceCollection()
            ->addFieldToFilter($field,$value)
            ->load()
            ->getItems();

        foreach ($savedCreditCardCollection as $savedCreditCard) {
            $savedCreditCard->delete();
        }
    }

    public function getCurrentCustomerSavedCards() {
        //This function looks like an repository funcion...
        $savedCreditCardCollection = [];

        $session = Mage::getSingleton('customer/session');

        if ($session->isLoggedIn()) {
            $customerId = $session->getCustomer()->getId();

            $savedCreditCardModel = Mage::getModel('paymentmodule/savedcreditcard');
            $savedCreditCardCollection = $savedCreditCardModel->getResourceCollection()
                ->addFieldToFilter('customer_id',$customerId)
                ->load()
                ->getItems();
        }

        return $savedCreditCardCollection;
    }

    public function isSavedCreditCardsEnabled()
    {
        $cardsConfig = Mage::getModel('paymentmodule/config_card');

        return $cardsConfig->isEnabled() === '1' && $cardsConfig->isSavedCreditCardsEnabled() === '1';
    }
}
