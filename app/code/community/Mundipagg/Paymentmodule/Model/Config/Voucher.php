<?php

class Mundipagg_Paymentmodule_Model_Config_Voucher
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/voucher_group/voucher_config_status');
    }

    public function getTitle()
    {
        return Mage::getStoreConfig('mundipagg_config/voucher_group/voucher_payment_title');
    }

    public function getPaymentTitle()
    {
        return $this->getTitle();
    }

    public function getInvoiceName()
    {
        return Mage::getStoreConfig('mundipagg_config/voucher_group/invoice_name');
    }

    public function getOperationType()
    {
        return Mage::getStoreConfig('mundipagg_config/voucher_group/operation_type');
    }

    public function getOperationTypeFlag()
    {
        return $this->getOperationType() === 'AuthAndCapture';
    }


    public function getBrandStatuses()
    {
        $installmentsConfig = $this->getInstallmentsConfig();

        return [
            'vr' => $installmentsConfig['vr_status'],
            'sodexo' => $installmentsConfig['sodexo_status']
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

    public function isVrEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/vr_status');
    }

    public function isSodexoEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/installments_group/sodexo_status');
    }
}
