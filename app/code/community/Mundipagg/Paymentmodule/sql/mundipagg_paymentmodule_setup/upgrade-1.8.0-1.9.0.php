<?php
$installer = $this;

$installer->startSetup();

$prefix = Mage::getConfig()->getTablePrefix();

$installer->run('
    CREATE TABLE IF NOT EXISTS 
    `' . $prefix . 'paymentmodule_recurrencetemplate` (
        `id`                    INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
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

$installer->run("
    CREATE TABLE IF NOT EXISTS 
    `' . $prefix . 'paymentmodule_recurrencetemplaterepetition` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `template_id` INT NOT NULL,
        `cycles` INT NOT NULL,
        `frequency` INT NOT NULL,
        `interval_type` CHAR NOT NULL,
        `discount_type` CHAR NOT NULL,
        `discount_value` FLOAT NOT NULL,
        PRIMARY KEY (`id`, `template_id`),
        INDEX `fk_template_repetition_template1_idx` (`template_id` ASC),
        CONSTRAINT `fk_template_repetition_template1`
        FOREIGN KEY (`template_id`)
        REFERENCES `" . $prefix . "paymentmodule_recurrencetemplate` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
"
);

$installer->run('
    CREATE TABLE IF NOT EXISTS 
    `' . $prefix . 'paymentmodule_recurrenceproduct` (
        `id`                    int PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `created_at`            TIMESTAMP    DEFAULT current_timestamp, 
        `updated_at`            TIMESTAMP    DEFAULT current_timestamp ON UPDATE current_timestamp
    )
'
);

$installer->run("
    CREATE TABLE IF NOT EXISTS 
    `' . $prefix . 'paymentmodule_recurrencesubproduct` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `recurrency_product_id` INT NOT NULL,
        `product_id` INT NOT NULL,
        `quantity` INT NOT NULL,
        `cycles` INT NOT NULL,
        `cycle_type` CHAR NOT NULL,
        `unit_price_in_cents` INT NOT NULL,
        PRIMARY KEY (`id`),
        INDEX `fk_recurrency_subproduct_recurrency_product1_idx` (`recurrency_product_id` ASC),
        CONSTRAINT `fk_recurrency_subproduct_recurrency_product1`
        FOREIGN KEY (`recurrency_product_id`)
        REFERENCES `" . $prefix . "paymentmodule_recurrenceproduct` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
"
);

$installer->run("
    CREATE TABLE IF NOT EXISTS 
    `' . $prefix . 'paymentmodule_recurrenceproduct` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `is_disabled` TINYINT NOT NULL DEFAULT 0,
        `product_id` INT NOT NULL,
        `template_snapshot` TEXT NOT NULL,
        `template_id` INT NULL,
        `mundipagg_plan_id` VARCHAR(45) NULL,
        `mundipagg_plan_status` VARCHAR(45) NULL,
        `is_single` TINYINT NOT NULL,
        `price` INT NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        INDEX `fk_plan_template1_idx` (`template_id` ASC),
        CONSTRAINT `fk_plan_template1`
        FOREIGN KEY (`template_id`)
        REFERENCES `" . $prefix . "paymentmodule_recurrencetemplate` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
"
);

$installer->endSetup();