<?php

class Mundipagg_Paymentmodule_Model_SavedCreditCard extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('mundipagg_paymentmodule/saved_credit_card');
    }

    public function save($payments, $response)
    {
        if (empty($response->charges)) {
            throw new \Exception('Charge not found');
        }

        for ($i = 0; $i < count($payments); $i++) {
            if (isset($payments->payments[$i]->metadata['save'])) {
                $this->saveCard($response->charges[$i]->lastTransaction->card);
            }
        }
    }

    private function saveCard($card)
    {
        
    }
}