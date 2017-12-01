<?php

class Mundipagg_Paymentmodule_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
    /**
     * This method defines the controller that will be called when the 'place order' button
     * is pressed, in this case, Mundipagg_Paymentmodule_StandardController, and the specific
     * method, redirectAction.
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        $controller = '';

        switch ($this->getCode()) {
            case 'paymentmodule_boleto':
                $controller = 'boleto';
                break;
            case 'paymentmodule_creditcard':
                $controller = 'creditcard';
                break;
            default:
                // @todo log error and redirect user to failure page
        }

        // @fixme _secure is set to false because we are in dev mode
        return Mage::getUrl(
            'paymentmodule/' . $controller . '/processpayment',
            array('_secure' => false)
        );
    }

    public function authorize(Varien_Object $payment, $amount)
    {
    }

    public function getCheckoutSession()
    {
        return Mage::getModel('checkout/session');
    }

    public function getCustomerSession()
    {
        return Mage::getModel('customer/session');
    }

    /**
     * Increment order ids are those ids in the form '100000104'
     *
     * @param string $orderId
     * @return string
     */
    public function getOrderByIncrementOrderId($orderId)
    {
        return Mage::getModel('sales/order')->loadByIncrementId($orderId);
    }

    public function getOrderByOrderId($orderId)
    {
        return Mage::getModel('sales/order')->load($orderId);
    }

    /**
     * Retrieves additional information for order represented by real order
     * id passed as argument
     *
     * @param string $orderId
     * @return array
    */
    public function getAdditionalInformationForOrder($orderId)
    {
        $order = $this->getOrderByIncrementOrderId($orderId);

        return $order->getPayment()->getAdditionalInformation();
    }
}
