<?php

class Mundipagg_Paymentmodule_Model_Standard extends Mage_Payment_Model_Method_Abstract
{

    public function isAvailable($quote = null)
    {
        return $this->getConfigModel()->isEnabled();
    }

    public function getPaymentTitle()
    {
        return $this->getConfigModel()->getPaymentTitle();
    }

    protected function getConfigModel()
    {
        throw new \Exception(
            "Magento tries to instantiate this class," .
            "so this method can't be abstract. " .
            "It must be implemented in child classes."
        );
    }

    public function assignData($data)
    {
        $paymentMethod = $data->getMethod();
        $paymentData = $this->getPaymentData($data->getData(), $paymentMethod);
        $this->validate($paymentData);
        try {
            $info = $this->getInfoInstance();
            $info->setAdditionalInformation(
                'mundipagg_payment_method',
                $paymentMethod
            );
            $info->setAdditionalInformation($paymentMethod, $paymentData);
        } catch (Mage_Core_Exception $e) {
            // @todo log it and do something
        }

        return $this;
    }

    protected function getPaymentData($data, $paymentMethod)
    {
        $result = array_filter(
            $data,
            function ($k) use ($paymentMethod) {
                return preg_match('/^' . $paymentMethod . '/', $k);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $this->formatPaymentData($result, $paymentMethod);
    }

    protected function formatPaymentData($data, $paymentMethod)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $keys = explode($paymentMethod .'_', $key)[1];
            $keys = explode('_', $keys);

            $result[$keys[0]][$keys[1]][$keys[2]] = $value;
        }

        return $result;
    }

    /**
     * This method defines the controller that will be called when the 'place order' button
     * is pressed, in this case, Mundipagg_Paymentmodule_StandardController, and the specific
     * method, redirectAction.
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        // @fixme _secure is set to false because we are in dev mode
        return Mage::getUrl(
            'paymentmodule/payment/processpayment',
            array('_secure' => false)
        );
    }

    public function getCheckoutSession()
    {
        return Mage::getModel('checkout/session');
    }

    public function getCustomerSession()
    {
        return Mage::getModel('customer/session');
    }

    public function getRegionModel()
    {
        return Mage::getModel('directory/region');
    }

    /**
     * Increment order ids are those ids in the form '100000104'
     *
     * @param string $orderId
     * @return string
     * @throws Varien_Exception
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

    /**
     * @param $charges
     * @param $orderId
     * @throws Varien_Exception
     */
    public function addChargeInfoToAdditionalInformation($charges, $orderId)
    {
        $order   = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $payment = $order->getPayment();

        foreach ($charges as $charge) {
            $newInfo[$charge->id] =
                json_decode(json_encode($charge), true);
        }

        if (!empty($newInfo)) {
            $payment->setAdditionalInformation(
                'mundipagg_payment_module_charges',
                $newInfo
            );
            $payment->save();
        }
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function getPaymentFromOrder($orderId)
    {
        $order = $this->getOrderByOrderId($orderId);

        return $order->getPayment();
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function getChargeInfoFromAdditionalInformation($orderId)
    {
        $payment = $this->getPaymentFromOrder($orderId);

        return $payment->getAdditionalInformation();
    }

    public function getOrderFromCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Prevent not allowed input data
     * @return $this|Mage_Payment_Model_Abstract
     * @throws Mage_Core_Exception
     * @todo Improve this method
     */
    public function validate($paymentData = null)
    {
        if (!$paymentData) {
            return $this;
        }

        $validation = true;

        if (isset($paymentData['creditcard'])) {
            $validation = $this->validateCreditCard($paymentData['creditcard']);
        }

        if (!$validation) {
            Mage::throwException(Mage::helper('paymentmodule')->__('Invalid payment data'));
            return false;
        }
    }

    private function validateCreditCard($creditCards)
    {
        $enabledBrands = strtolower($this->getConfigModel()->getEnabledBrands());

        foreach ($creditCards as $creditCard) {
            if (
                !in_array(
                    $enabledBrands,
                    strtolower($creditCard['brand'])
                ) && !$this->validateInstallments($creditCard)

            ) {
                return false;
            }
        }

        return true;
    }

    private function validateInstallments($card)
    {
        $configModel = $this->getConfigModel();
        $default = $configModel->isDefaultConfigurationEnabled();

        $installments = $card['creditCardInstallments'];
        $brand = $card['brand'];

        if (
            $default &&
            $installments <= $configModel->getDefaultMaxInstallmentNumber()
        ) {
            return true;
        }

        $brandMaxInstallments = 'get' . $brand . 'MaxInstallmentsNumber';

        if ($installments > $configModel->$brandMaxInstallments()) {
            return false;
        }

        return true;
    }
}