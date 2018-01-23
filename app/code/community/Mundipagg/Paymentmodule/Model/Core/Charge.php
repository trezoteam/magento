<?php

/**
 * Class Mundipagg_Paymentmodule_Model_Core_Charge
 */
class Mundipagg_Paymentmodule_Model_Core_Charge extends Mundipagg_Paymentmodule_Model_Core_Base
{
    /**
     * @param $webHook
     * @throws Exception
     */
    protected function created($webHook)
    {
        $helper = $this->getHelper();
        $helper->updateChargeInfo(__FUNCTION__, $webHook);
    }

    /**
     * @param $webHook
     */
    protected function paid($webHook)
    {
        $helper = $this->getHelper();
        $helper->paidMethods(__FUNCTION__, $webHook);
    }

    /**
     * @param $webHook
     */
    protected function overpaid($webHook)
    {
        $helper = $this->getHelper();
        $helper->paidMethods(__FUNCTION__, $webHook);
    }

    /**
     * @param $webHook
     */
    protected function underpaid($webHook)
    {
        $helper = $this->getHelper();
        $helper->paidMethods(__FUNCTION__, $webHook);
    }

    private function getHelper()
    {
        return Mage::helper('paymentmodule/chargeoperations');
    }
}