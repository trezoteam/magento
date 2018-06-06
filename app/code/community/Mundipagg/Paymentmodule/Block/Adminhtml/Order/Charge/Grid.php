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
        array_walk($aditional['mundipagg_payment_module_charges'],
            function ($item) use ($collection) {
                $item['amount'] = $item['amount'] / 100;

                if (!isset($item['paid_amount'])) {
                    $item['paid_amount'] =  0.000000001;
                    if ($item['last_transaction']['operation_type'] == 'capture') {
                        $item['paid_amount'] = $item['last_transaction']['amount'];
                    }
                }
                $item['paid_amount'] = $item['paid_amount'] / 100;

                $item['canceled_amount'] =  0.000000001;
                if ($item['last_transaction']['operation_type'] == 'cancel') {
                    $item['canceled_amount'] = $item['last_transaction']['amount'] / 100;
                }

                $rowObj = new Varien_Object();
                $rowObj->setData($item);
                $collection->addItem($rowObj);
            }
        );

        $items = [];
        foreach($aditional['mundipagg_payment_module_charges'] as $item) {
            $items[] = $item;//->getData();
        }

        return $collection;
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
