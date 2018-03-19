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

        $standard = Mage::getModel('paymentmodule/standard');
        $orderId‌ = $response->code;

        $additionalInformation = $standard->getAdditionalInformationForOrder($orderId‌);
        $paymentMethod = $additionalInformation['mundipagg_payment_method'];
        $cards = $additionalInformation[$paymentMethod]['creditcard'];

        foreach ($cards as $key => $card) {
            if (isset($card['saveCreditCard']) && $card['saveCreditCard'] === 'on') {

                $this->save(
                    $response->charges[$key -1]->lastTransaction->card,
                    $response->charges[$key -1]->customer->id
                );
            }
        }
    }

    /**
     * @param stdClass $card
     * @param string $mundipaggCustomerId
     * @throws Exception
     */
    public function save($card, $mundipaggCustomerId)
    {
        $saveCreditCard = Mage::getModel('paymentmodule/savedcreditcard');

        try {
            $saveCreditCard->setMundipaggCardId($card->id);
            $saveCreditCard->setMundipaggCustomerId($mundipaggCustomerId);
            $saveCreditCard->setHolderName($card->holderName);
            $saveCreditCard->setBrandName($card->brand);
            $saveCreditCard->setFirstSixDigits($card->firstSixDigits);
            $saveCreditCard->setLastFourDigits($card->lastFourDigits);
            $saveCreditCard->setExpirationMonth($card->expMonth);
            $saveCreditCard->setExpirationYear($card->expYear);
            $saveCreditCard->save();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}