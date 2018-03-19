<?php

class Mundipagg_Paymentmodule_Model_Savedcreditcard extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('paymentmodule/savedcreditcard');
    }

    public function saveIt($response)
    {
        if (empty($response->charges)) {
            throw new \Exception('Charge not found');
        }

        $standard = Mage::getModel('paymentmodule/standard');
        $orderId‌ = $response->code;

        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId‌);
        $cards = $additionalInformation['paymentmodule_creditcard']['creditcard'];

        foreach ($cards as $key => $card) {
            if ($card['saveCreditCard'] === 'on') {
                $this->saveCard(
                    $response->charges[$key -1]->lastTransaction->card,
                    $response->charges[$key -1]->customer->id
                );
            }
        }
    }

    private function saveCard($card, $mundipaggCustomerId)
    {
        $this->setMundipaggCardId($card->id);
        $this->setMundipaggCustomerId($mundipaggCustomerId);
        $this->setHolderName($card->holderName);
        $this->setBrandName($card->brand);
        $this->setFirstSixDigits($card->firstSixDigits);
        $this->setLastFourDigits($card->lastFourDigits);
        $this->setExpirationMonth($card->expirationMonth);
        $this->setExpirationYear($card->expirationYear);
        $this->save();
    }
}