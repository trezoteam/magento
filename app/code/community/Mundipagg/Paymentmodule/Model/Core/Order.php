<?php

class Mundipagg_Paymentmodule_Model_Core_Order  extends Mundipagg_Paymentmodule_Model_Core_Base
{

    protected $infoInstance;

    //Do nothing
    protected function created($webHook)
    {
    }

    /**
     * Set order status as processing
     * Order invoice is created by charge webhook
     * @param stdClass $webHook
     * @throws Varien_Exception
     */
    protected function paid($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $order = $standard->getOrderByIncrementOrderId($webHook->code);

        if ($order->getState() != Mage_Sales_Model_Order::STATE_PROCESSING) {
            $order
                ->setState(
                    Mage_Sales_Model_Order::STATE_PROCESSING,
                    true,
                    '',
                    true
                );
            $order->save();
        }
    }

    /**
     * @param stdClass $webHook
     * @throws Varien_Exception
     */
    protected function canceled($webHook)
    {
        $standard = Mage::getModel('paymentmodule/standard');
        $invoiceHelper = Mage::helper('paymentmodule/invoice');

        $order = $standard->getOrderByIncrementOrderId($webHook->code);

        if ($order->canUnhold()) {
            $order->unhold();
        }

        if ($invoiceHelper->cancelInvoices($order)) {
            $this->closeOrder($order);
        }

        $order
            ->setState(
                Mage_Sales_Model_Order::STATE_CANCELED,
                true,
                '',
                true
            );
        $order->save();
    }


    protected function paymentFailed($webHook)
    {
        $this->canceled($webHook);
    }


    /**
     * @param object $order
     */
    protected function closeOrder($order)
    {
        $order->setData('state', Mage_Sales_Model_Order::STATE_CLOSED);
        $order->setStatus(Mage_Sales_Model_Order::STATE_CLOSED);
        $order->sendOrderUpdateEmail();
        $order->save();
    }

    public function processOrderAmountChanges(&$paymentData)
    {
        $this->getPaymentHelper()->validate($paymentData);
        $this->applyInterest($paymentData);
        return $paymentData;
    }

    public function applyInterest(&$paymentData)
    {
        $interest = 0;
        foreach ($paymentData as $method => $data) {
            $interest = $this->getInterests($data, $method);
            $paymentData[$method] = $data;
        }

        $this->applyInterestOnSession($interest);
        return $paymentData;
    }

    protected function getInterests(&$data, $method)
    {
       if ($method != 'creditcard') {
            return $data;
        }
        $totalInterest = 0;

        $data = array_map(function ($item) use (&$totalInterest) {
            $interest = $this->getInterestHelper()
                ->getInterestValue(
                    $item['creditCardInstallments'],
                    $item['value'],
                    $this->getConfigCards()->getEnabledBrands(),
                    $item['brand']
                );
            $item['value'] = $item['value'] + $interest;
            $totalInterest += $interest;

            return $item;
        },$data);

        return $totalInterest;
    }

    protected function applyInterestOnSession($interest)
    {
        $addresses = $this->getInfoInstance()->getQuote()->getAllAddresses();

        foreach ($addresses as $address) {
            $grandTotal = $address->getGrandTotal();
            if ($grandTotal) {
                $address->setMundipaggInterest($interest);
                $address->setGrandTotal($grandTotal + $interest);
            }
        }
    }

    protected function getConfigCards()
    {
        return Mage::getModel('paymentmodule/config_card');
    }

    protected function getInterestHelper()
    {
        return Mage::helper('paymentmodule/interest');
    }

    protected function getPaymentHelper()
    {
        return Mage::helper('paymentmodule/paymentformat');
    }

    public function setInfoInstance($info)
    {
        $this->infoInstance = $info;
        return $this;
    }

    protected function getInfoInstance()
    {
        return $this->infoInstance;
    }

}
