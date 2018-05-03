<?php

class Mundipagg_Paymentmodule_Block_Base extends Mage_Payment_Block_Form
{
    public function getCurrentCurrencySymbol()
    {
        return Mage::app()
            ->getLocale()
            ->currency(
                Mage::app()
                    ->getStore()
                    ->getCurrentCurrencyCode()
            )
            ->getSymbol();
    }
}