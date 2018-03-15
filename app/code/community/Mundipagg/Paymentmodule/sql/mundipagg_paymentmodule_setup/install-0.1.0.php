<?php

$installer = $this;

$installer->startSetup();

$prefix = Mage::getConfig()->getTablePrefix();

$installer->run("
CREATE TABLE mundipagg_saved_credit_card
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    mundipagg_card_id VARCHAR(50) NOT NULL,
    brand VARCHAR(6),
    first_six_digits INT,
    last_four_digit INT
);
CREATE UNIQUE INDEX mundipagg_saved_credit_card_id_uindex ON mundipagg_saved_credit_card (id);
CREATE UNIQUE INDEX mundipagg_saved_credit_card_mundipagg_card_id_uindex ON mundipagg_saved_credit_card (mundipagg_card_id);
");

$installer->endSetup();
