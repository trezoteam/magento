<?php

class Mundipagg_Paymentmodule_Block_Adminhtml_Recurrence_Template_PaymentMethodColumn
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $acceptBoleto = $row->getData('accept_boleto');
        $acceptCreditCard = $row->getData('accept_credit_card');

        $data = [];
        if ($acceptBoleto) {
            $data[] = '<span>Boleto</span>';
        }

        if ($acceptCreditCard) {
            $data[] = '<span>Credit Card</span>';
        }

        return implode(" / ", $data);
    }
}