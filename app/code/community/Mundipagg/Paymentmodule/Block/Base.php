<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 02/04/18
 * Time: 11:42
 */

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