<?php

class Mundipagg_Paymentmodule_Model_Config_Recurrence
{
    public function isSingleEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/recurrence_group/enable_single_subscription') == 1;
    }

    public function isPlanEnabled()
    {
        return Mage::getStoreConfig('mundipagg_config/recurrence_group/enable_subscriptino_by_plan') == 1;
    }

    public function isPaymentUpdateCustomerEnable()
    {
        return Mage::getStoreConfig('mundipagg_config/recurrence_group/enable_payment_method_update_by_customer') == 1;
    }

    public function isSubscritionInstallmentEnable()
    {
        return Mage::getStoreConfig('mundipagg_config/recurrence_group/enable_subscription_installments') == 1;
    }

    public function getCheckoutConflictMessage()
    {
        return Mage::getStoreConfig('mundipagg_config/recurrence_group/checkout_conflict_method') == 1;
    }

    public function getAllSettings()
    {
        return [
            'recurrence_singleSubscription' => $this->isSingleEnabled(),
            'recurrence_subscriptionByPlan' => $this->isPlanEnabled(),
            'recurrence_paymentUpdateCustomer' => $this->isPaymentUpdateCustomerEnable(),
            'recurrence_subscriptionInstallment' => $this->isSubscritionInstallmentEnable(),
            'recurrence_checkoutConflictMessage' => $this->getCheckoutConflictMessage()
        ];
    }
}