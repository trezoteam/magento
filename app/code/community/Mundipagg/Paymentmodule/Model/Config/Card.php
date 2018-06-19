<?php


class Mundipagg_Paymentmodule_Model_Config_Card
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/creditcard_group/cards_config_status');
    }

    public function getTitle()
    {
        return Mage::getStoreConfig('mundipagg_config/creditcard_group/creditcard_payment_title');
    }

    public function getPaymentTitle()
    {
        return $this->getTitle();
    }

    public function getInvoiceName()
    {
        return Mage::getStoreConfig('mundipagg_config/creditcard_group/invoice_name');
    }

    public function getOperationType()
    {
        return Mage::getStoreConfig('mundipagg_config/creditcard_group/operation_type');
    }

    public function getOperationTypeFlag()
    {
        return $this->getOperationType() === 'AuthAndCapture';
    }

    public function getInstallmentsConfig()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group');
    }

    public function getBrandStatuses()
    {
        $installmentsConfig = $this->getInstallmentsConfig();

        return [
            'amex' => $installmentsConfig['amex_status'],
            'diners' => $installmentsConfig['diners_status'],
            'elo' => $installmentsConfig['elo_status'],
            'hipercard' => $installmentsConfig['hipercard_status'],
            'mastercard' => $installmentsConfig['mastercard_status'],
            'visa' => $installmentsConfig['visa_status']
        ];
    }

    public function getEnabledBrands()
    {
        $brandStatuses = $this->getBrandStatuses();
        $enabledBrands = [];

        foreach ($brandStatuses as $brand => $status) {
            if ($status == 1) {
                $enabledBrands[] = $brand;
            }
        }

        return $enabledBrands;
    }

    public function isDefaultConfigurationEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/default_status');
    }

    public function getDefaultMaxInstallmentNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/default_max_installments');
    }

    public function getDefaultMaxInstallmentNumberWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/default_max_without_interest');
    }

    public function getDefaultInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/default_interest');
    }

    public function getDefaultIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/default_incremental_interest');
    }

    public function isVisaEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/visa_status');
    }

    public function getVisaInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/visa_interest');
    }

    public function getVisaMaxInstallmentsWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/visa_max_no_interest');
    }

    public function getVisaMaxInstallmentsNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/visa_max_installments');
    }

    public function getVisaIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/visa_incremental_interest');
    }

    public function isMastercardEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/mastercard_status');
    }

    public function getMastercardInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/master_interest');
    }

    public function getMastercardMaxInstallmentsWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/mastercard_max_no_interest');
    }

    public function getMastercardMaxInstallmentsNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/mastercard_max_installments');
    }

    public function getMastercardIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/mastercard_incremental_interest');
    }

    public function isHipercardEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/hipercard_status');
    }

    public function getHipercardInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/hipercard_interest');
    }

    public function getHipercardMaxInstallmentsWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/hipercard_max_no_interest');
    }

    public function getHipercardMaxInstallmentsNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/hipercard_max_installments');
    }

    public function getHipercardIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/hipercard_incremental_interest');
    }

    public function isDinersEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/diners_status');
    }

    public function getDinersInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/diners_interest');
    }

    public function getDinersMaxInstallmentsWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/diners_max_no_interest');
    }

    public function getDinersMaxInstallmentsNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/diners_max_installments');
    }

    public function getDinersIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/diners_incremental_interest');
    }

    public function isAmexEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/amex_status');
    }

    public function getAmexInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/amex_interest');
    }

    public function getAmexMaxInstallmentsWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/amex_max_no_interest');
    }

    public function getAmexMaxInstallmentsNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/amex_max_installments');
    }

    public function getAmexIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/amex_incremental_interest');
    }

    public function isEloEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/elo_status');
    }

    public function getEloInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/default_interest');
    }

    public function getEloMaxInstallmentsWithoutInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/elo_max_no_interest');
    }

    public function getEloMaxInstallmentsNumber()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/elo_max_installments');
    }

    public function getEloIncrementalInterest()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/elo_incremental_interest');
    }

    public function isSavedCreditCardsEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/creditcard_group/saved_cards_status');
    }
}
