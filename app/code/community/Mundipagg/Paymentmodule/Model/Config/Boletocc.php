<?php

class Mundipagg_Paymentmodule_Model_Config_Boletocc
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_config_status') == 1;
    }

    public function getPaymentTitle()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_creditcard_payment_title');
    }

    public function getName()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_name');
    }

    public function getBank()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_bank');
    }

    /**
     * This method returns a string date formatted according to iso-8601
     *
     * @return string
     */
    public function getDueAt()
    {
        $term = Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_due_at');
        return new DateTime(date('Y-m-d', strtotime('+' . $term . ' days')));
    }

    public function getInstructions()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_instructions');
    }

    /** Card configs */

    public function getInvoiceName()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_invoice_name');
    }

    public function getOperationType()
    {
        return Mage::getStoreConfig('mundipagg_config/boletocreditcard_group/boleto_cards_operation_type');
    }

    public function getOperationTypeFlag()
    {
        return $this->getOperationType() === 'AuthAndCapture';
    }


}
