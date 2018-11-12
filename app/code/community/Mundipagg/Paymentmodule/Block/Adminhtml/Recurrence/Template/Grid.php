<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('recurrence_template');
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
        $model = Mage::getModel('paymentmodule/recurrencetemplate');
        $collection = $model->getResourceCollection()
            ->addFieldToFilter('is_disabled', array('eq' => 0))
            ->load();

        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('paymentmodule/order');

        $this->addColumn('id', array(
            'header' => $this->__('Id'),
            'index'  => 'id',
            'filter' => false,
            'sortable'  => false,
        ));

        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'index'  => 'name',
            'filter' => false,
            'sortable'  => false,
        ));

        $this->addColumn('type', array(
            'header' => $this->__('Type'),
            'index'  => 'type',
            'filter' => false,
            'sortable'  => false,
            'renderer' => 'Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_TemplateTypeColumn'
        ));

        $this->addColumn('description', array(
            'header' => $this->__('Description'),
            'index'  => 'description',
            'filter' => false,
            'sortable'  => false,
        ));

        $this->addColumn('accept_credit_card', array(
            'header' => $this->__('Payment Methods'),
            'index'  => 'payment_methods',
            'filter' => false,
            'sortable'  => false,
            'renderer' => 'Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_PaymentMethodColumn'
        ));

        $this->addColumn('action_delete', array(
            'header' => $helper->__(''),
            'width'     => '5%',
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => array(
                array(
                    'caption' => $this->__('Delete'),
                    'url' => [
                        'base' => 'adminhtml/recurrencetemplate/delete',
                        'params' => ['id' => $this->getId()]
                    ],
                    'field'   => 'id',
                    'class'   => 'form-button'
                ),

            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        $this->addColumn('action_edit', array(
            'header' => $helper->__(''),
            'width'     => '5%',
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => array(
                array(
                    'caption' => $this->__('Edit'),
                    'url' => [
                        'base' => 'adminhtml/recurrencetemplate/edit',
                        'params' => [ 'id' => $this->getId() ]
                    ],
                    'field'   => 'id',
                    'class'   => 'form-button'
                ),

            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current'=>true]);
    }
}
