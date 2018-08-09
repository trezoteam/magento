<?php
 
class Mundipagg_Paymentmodule_Block_Adminhtml_Order_Charge_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_charge_grid');
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        $this->setSaveParametersInSession(true);
    }

    public function getRowUrl($row)
    {
       return false;
    }
 
    protected function _prepareCollection()
    {
        $orderId = Mage::app()->getRequest()->getParam('order_id');

        $collection = Mage::getResourceModel('sales/order_collection')
            ->join(['b' => 'sales/order_payment'],
                'main_table.entity_id = b.parent_id',
                ['additional_information' => 'additional_information']
            )
            ->addFieldToFilter('main_table.entity_id', $orderId);

        $collection = $this->createChargeCollection($collection);
        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    protected function createChargeCollection($collection)
    {
        $aditional = [];
        foreach ($collection as $order) {
            $aditional = unserialize($order->additional_information);
        }

        $collection = new Varien_Data_Collection();

        if (isset($aditional['mundipagg_payment_module_charges'])) {

            array_walk($aditional['mundipagg_payment_module_charges'],
                [$this, 'createCollection'],
                ['collection' => $collection, 'aditional' => $aditional]
            );
        }

        return $collection;
    }

    /* The second parameter ($key) does not used but is passed by array_walk function to the anonymous function */
    public function createCollection($item, $key, $params)
    {

        $item['amount'] = $item['amount'] / 100;

        $chargeHistory = array_filter(
            $params['aditional']['mundipagg_payment_transaction_history'],
            function($history) use ($item){
                return $item['id'] == $history['chargeId'];
            }
        );

        $captureTransactions = array_filter(
            $chargeHistory,
            function($history) {
                return strpos($history['type'], 'capture') !== false;
            }
        );

        $item['paid_amount'] = 0.000000001;
        foreach ($captureTransactions as $capture) {
            $item['paid_amount'] += $capture['amount'];
        }
        $item['paid_amount'] = $item['paid_amount'] / 100;

        $canceledTransactions = array_filter(
            $chargeHistory,
            function($history) {
                return $history['type'] == 'cancel';
            }
        );

        $item['canceled_amount'] = 0.000000001;
        foreach ($canceledTransactions as $canceled) {
            $item['canceled_amount'] += $canceled['amount'];
        }
        $item['canceled_amount'] = $item['canceled_amount'] / 100;

        if ($item['canceled_amount'] > $item['amount']) {
            $item['canceled_amount'] = $item['amount'];
        }

        $rowObj = new Varien_Object();
        $rowObj->setData($item);
        return $params['collection']->addItem($rowObj);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('paymentmodule/order');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
 
        $this->addColumn('id', [
            'header' => $this->__('Charge Id'),
            'index'  => 'id',
            'filter' => false,
            'sortable'  => false,
        ]);
 
        $this->addColumn('amount', [
            'header' => $this->__('Amount'),
            'index'  => 'amount',
            'type'   => 'currency',
            'currency_code' => $currency,
            'filter' => false,
            'sortable'  => false
        ]);

        $this->addColumn('paid_amount', [
            'header' => $this->__('Captured Amount'),
            'index'  => 'paid_amount',
            'type'   => 'currency',
            'currency_code' => $currency,
            'filter' => false,
            'sortable'  => false
        ]);

        $this->addColumn('canceled_amount', [
            'header' => $this->__('Canceled Amount'),
            'index'  => 'canceled_amount',
            'type'   => 'currency',
            'currency_code' => $currency,
            'filter' => false,
            'sortable'  => false
        ]);
 
        $this->addColumn('status', [
            'header' => $helper->__('Status'),
            'index'  => 'status',
            'filter' => false,
            'sortable'  => false
        ]);
 
        $this->addColumn('payment_method', [
            'header' => $this->__('Payment Method'),
            'index'  => 'payment_method',
            'filter' => false,
            'sortable'  => false
        ]);

        $this->addColumn('action_capture', [
            'header' => $helper->__(''),
            'width'     => '5%',
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => [
                [
                    'caption' => $this->__('Capture'),
                    'onclick' => 'javascript:showChargeDialog("Capture",this);',
                    'field'   => 'id',
                    'class'   => 'form-button'
                ]
            ],
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ]);

        $this->addColumn('action_cancel', [
            'header' => $helper->__(''),
            'width'     => '5%',
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => [
                [
                    'caption' => $this->__('Cancel'),
                    'onclick' => 'javascript:showChargeDialog("Cancel",this);',
                    'field'   => 'id',
                    'class'   => 'form-button'
                ]
            ],
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ]);

        return parent::_prepareColumns();
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current'=>true]);
    }
}
