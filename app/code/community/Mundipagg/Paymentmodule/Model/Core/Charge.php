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

    /**
     * @param $webHook
     */
    protected function canceled($webHook)
    {
        $helper = $this->getHelper();
        $helper->canceledMethods(__FUNCTION__, $webHook);
    }

    /**
     * Same as canceled
     * @param $webHook
     */
    protected function refunded($webHook)
    {
        $this->canceled($webHook);
    }

    /**
     * Same as canceled
     * @param $webHook
     */
    protected function paymentFailed($webHook)
    {
        $helper = $this->getHelper();
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');

        $helper
            ->canceledMethods(
            __FUNCTION__,
            $webHook,
            $orderEnum->notAuthorized()
        );
    }

    /**
     * @param $webHook
     */
    protected function partialRefunded($webHook)
    {
        $helper = $this->getHelper();
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');
        $helper
            ->canceledMethods(
                __FUNCTION__,
                $webHook,
                $orderEnum->chargeRefunded()
            );
    }

    /**
     * @param $webHook
     */
    protected function partialCanceled($webHook)
    {
        $helper = $this->getHelper();
        $orderEnum = Mage::getModel('paymentmodule/enum_orderhistory');

        $helper->canceledMethods(
            __FUNCTION__,
            $webHook,
            $orderEnum->chargePartialCanceled()
        );
    }

    private function getHelper()
    {
        return Mage::helper('paymentmodule/chargeoperations');
    }
}