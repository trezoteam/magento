<?php

class Mundipagg_Paymentmodule_BoletoccController extends Mundipagg_Paymentmodule_Controller_Payment
{
    /**
     * Gather boleto transaction information and try to create
     * payment using sdk api wrapper.
     */
    public function processPaymentAction()
    {
        die ("ok");
    }

    /**
     * Gather information about payment
     *
     * @return Varien_Object
     */
    private function getPaymentInformation()
    {

    }
}
