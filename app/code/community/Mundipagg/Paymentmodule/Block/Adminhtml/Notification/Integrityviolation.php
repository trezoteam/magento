<?php

use Mundipagg\Integrity\IntegrityController;

class Mundipagg_Paymentmodule_Block_Adminhtml_Notification_Integrityviolation extends Mage_Adminhtml_Block_Template
{
    public function __construct(array $args = array())
    {
        parent::_construct($args);
        $this->setTemplate('paymentmodule/notifications/integrityViolation.phtml');
    }

    public function isViolated()
    {
        require_once Mage::getBaseDir('lib') . '/autoload.php';
        $integrityController = new IntegrityController(
            \Mage::helper('paymentmodule/MagentoSystemInfo'),
            \Mage::helper('paymentmodule/MagentoOrderInfo')
        );
        $integrityCheck = $integrityController->getIntegrityCheck();

        return
            count($integrityCheck['alteredFiles']) > 0 ||
            count($integrityCheck['newFiles']) > 0 ||
            count($integrityCheck['unreadableFiles']) > 0
        ;
    }
}
