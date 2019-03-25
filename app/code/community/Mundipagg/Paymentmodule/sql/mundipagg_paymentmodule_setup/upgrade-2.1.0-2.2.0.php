<?php
$installer = $this;

$installer->startSetup();

$prefix = Mage::getConfig()->getTablePrefix();

$installer->run("
          ALTER TABLE `" . $prefix . "paymentmodule_configuration`
          ADD store_id VARCHAR(255);
        "
);

$installer->endSetup();