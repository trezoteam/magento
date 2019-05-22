<?php
$installer = $this;

$installer->startSetup();
$prefix = Mage::getConfig()->getTablePrefix();

$table = $installer->getConnection()->addColumn(
    $prefix . "paymentmodule_configuration",
    'store_id',
    [
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length' => 255,
        'nullable' => true,
        'comment' => 'store id'
    ]
);

$installer->endSetup();