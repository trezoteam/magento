<?php

class Mundipagg_Paymentmodule_Model_Config_Boleto
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/boleto_group/boleto_status') == 1;
    }

    public function getPaymentTitle()
    {
        return Mage::getStoreConfig('mundipagg_config/boleto_group/boleto_payment_title');
    }

    public function getName()
    {
        return Mage::getStoreConfig('mundipagg_config/boleto_group/boleto_name');
    }

    public function getBank()
    {
        return Mage::getStoreConfig('mundipagg_config/boleto_group/boleto_bank');
    }

    /**
     * This method returns a string date formatted according to iso-8601
     *
     * @return string
     */
    public function getDueAt()
    {
        $term = Mage::getStoreConfig('mundipagg_config/boleto_group/boleto_due_at');
        $formattedDate = new DateTime(date('Y-m-d', strtotime('+' . $term . ' days')));

        return $formattedDate->format('c');
    }

    public function getInstructions()
    {
        return Mage::getStoreConfig('mundipagg_config/boleto_group/boleto_instructions');
    }
}
