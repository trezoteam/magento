<?php
$installer = $this;

$installer->startSetup();

$prefix = Mage::getConfig()->getTablePrefix();

$installer->run('
    CREATE TABLE IF NOT EXISTS 
    `' . $prefix . 'paymentmodule_recurrencetemplate` (
        `id`                    int PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `is_disabled`           TINYINT NOT NULL DEFAULT 0,
        `is_single`             TINYINT NOT NULL DEFAULT 0,
        `name`                  VARCHAR(45) NULL,
        `description`           TEXT NULL,
        `accept_credit_card`    TINYINT NOT NULL DEFAULT 0,
        `accept_boleto`         TINYINT NOT NULL DEFAULT 0,
        `allow_installments`    TINYINT NOT NULL DEFAULT 0,
        `due_type`              CHAR NOT NULL,
        `due_value`             TINYINT NOT NULL DEFAULT 0,            
        `trial`                 TINYINT NOT NULL DEFAULT 0,
        `installments`          VARCHAR(45) NULL,
        `created_at`            TIMESTAMP    DEFAULT current_timestamp, 
        `updated_at`            TIMESTAMP    DEFAULT current_timestamp ON UPDATE current_timestamp
    )
'
);

$installer->endSetup();