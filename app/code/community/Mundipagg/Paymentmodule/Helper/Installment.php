<?php

class Mundipagg_Paymentmodule_Helper_Installment extends Mage_Core_Helper_Abstract
{
    public function getInstallments($total)
    {
        $cardConfig = Mage::getModel('paymentmodule/config_card');

        if ($cardConfig->isDefaultConfigurationEnabled()) {
            return $this->getDefaultInstallments($total);
        }

        return $this->getCardsInstallments($total);
    }

    private function getDefaultInstallments($total)
    {
        $cardConfig = Mage::getModel('paymentmodule/config_card');

        $max = $cardConfig->getDefaultMaxInstallmentsNumber();
        $maxWithout = $cardConfig->getDefaultMaxInstallmentsWithoutInterest();
        $interest = $cardConfig->getDefaultInterest();
        $inc = $cardConfig->getDefaultIncrementalInterest();

        return array(
            'default' => array_merge(
                $this->getInstallmentsWithoutInterest($total, $maxWithout),
                $this->getInstallmentsWithInterest($total, $maxWithout, $max, $interest, $inc)
            )
        );
    }

    private function getCardsInstallments($total)
    {
        $cardConfig = Mage::getModel('paymentmodule/config_card');
        $cards = array('Visa', 'Master', 'Hiper', 'Diners', 'Amex', 'Elo');

        $installments = array();

        foreach ($cards as $card) {
            $enabled = 'is' . $card . 'Enabled';

            if ($cardConfig->$enabled) {
                $max = $cardConfig->{'get' . $card . 'MaxInstallmentsNumber'}();
                $maxWithout = $cardConfig->{'get' . $card . 'MaxInstallmentsWithoutInterest'}();
                $interest = $cardConfig->{'get' . $card . 'Interest'}();
                $inc = $cardConfig->{'get' . $card . 'IncrementalInterest'}();

                $installments[$card] = array_merge(
                    $this->getInstallmentsWithoutInterest($total, $maxWithout),
                    $this->getInstallmentsWithInterest($total, $maxWithout, $max, $interest, $inc)
                );
            }
        }
    }

    private function getInstallmentsWithoutInterest($total, $max)
    {
        $installments = array();

        for ($i = 0; $i < $max; $i++) {
            $installments[] = array(
                'amount' => $total / ($i + 1),
                'times' => $i + 1,
                'interest' => 0
            );
        }

        return $installments;
    }

    private function getInstallmentsWithInterest($total, $maxWithout, $max, $interest, $increment = 0)
    {
        $installments = array();

        for ($i = $maxWithout + 1; $i < $max; $i++) {
            $installments[] = array(
                'amount' => $total / ($i + 1),
                'times' => $i + 1,
                'interest' => $interest
            );

            $interest += $increment;
        }

        return $installments;
    }
}
