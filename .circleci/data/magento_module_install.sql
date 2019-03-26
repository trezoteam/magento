INSERT INTO magento.core_config_data (config_id, scope, scope_id, path, value)
VALUES (NULL , 'default', '0', 'dev/template/allow_symlink', '1');



CREATE TABLE IF NOT EXISTS `paymentmodule_hub_install_token` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `token` TEXT NOT NULL,
    `used` TINYINT(1) NOT NULL,
    `created_at_timestamp` INT NOT NULL,
    `expire_at_timestamp` INT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `paymentmodule_configuration` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` TEXT NOT NULL,
  `store_id` VARCHAR(255),
  PRIMARY KEY (`id`)
);

INSERT INTO paymentmodule_configuration (data, store_id) VALUES
('{"enabled":null,"antifraudEnabled":false,"antifraudMinAmount":0,"boletoEnabled":false,"creditCardEnabled":true,"twoCreditCardsEnabled":false,"boletoCreditCardEnabled":false,"testMode":true,"hubInstallId":"f09d7191-4349-4ac6-8ec7-17435b439cd5","addressAttributes":null,"keys":{"KEY_SECRET":"6a0dad395c2cf940027f23262c6f9247ab191c5b613dcf590e3e4a0cc12963b2","KEY_PUBLIC":"pk_test_yDo83Ls3rta3Z2jp"},"cardOperation":null,"installmentsEnabled":false,"cardStatementDescriptor":null,"boletoInstructions":null,"cardConfigs":[],"storeId":0,"methodsInherited":null,"parentId":null}', 0);


INSERT INTO magento.core_config_data (scope, scope_id, path, value) VALUES
-- module
('default', 0, 'mundipagg_config/general_group/module_status', '1'),
('default', 0, 'mundipagg_config/log_group/enabled', '1'),
('default', 0, 'mundipagg_config/general_group/sk_prod', null),
('default', 0, 'mundipagg_config/general_group/pk_prod', null),
('default', 0, 'mundipagg_config/general_group/test_mode', '1'),
('default', 0, 'mundipagg_config/antifraud_group/antifraud_status', '0'),
('default', 0, 'mundipagg_config/boleto_group/boleto_status', '1'),
('default', 0, 'mundipagg_config/boleto_group/boleto_payment_title', 'Mundipagg Boleto'),
('default', 0, 'mundipagg_config/boleto_group/boleto_name', 'boleto'),
('default', 0, 'mundipagg_config/boleto_group/boleto_bank', '001'),
('default', 0, 'mundipagg_config/boleto_group/boleto_due_at', '3'),
('default', 0, 'mundipagg_config/boleto_group/boleto_instructions', 'Pagar ate o vencimento.'),
('default', 0, 'mundipagg_config/twocreditcards_group/twocreditcards_status', '1'),
('default', 0, 'mundipagg_config/twocreditcards_group/twocreditcards_payment_title', 'Mundipagg Two Credit Cards'),
('default', 0, 'mundipagg_config/creditcard_group/cards_config_status', '1'),
('default', 0, 'mundipagg_config/creditcard_group/creditcard_payment_title', 'Mundipagg Credit Card'),
('default', 0, 'mundipagg_config/creditcard_group/invoice_name', 'Magento STG'),
('default', 0, 'mundipagg_config/creditcard_group/operation_type', 'AuthAndCapture'),
('default', 0, 'mundipagg_config/creditcard_group/saved_cards_status', '1'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_config_status', '1'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_creditcard_payment_title', 'Mundipagg Credit Card and Boleto'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_name', 'Boleto'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_bank', '001'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_due_at', '3'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_instructions', 'Pague ate o venciomento. (CC)'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_invoice_name', 'Magento STG BoletoCreditCArd'),
('default', 0, 'mundipagg_config/boletocreditcard_group/boleto_cards_operation_type', 'AuthAndCapture'),
('default', 0, 'mundipagg_config/installments_group/default_status', '1'),
('default', 0, 'mundipagg_config/installments_group/default_max_installments', '12'),
('default', 0, 'mundipagg_config/installments_group/default_max_without_interest', '5'),
('default', 0, 'mundipagg_config/installments_group/default_interest', '1'),
('default', 0, 'mundipagg_config/installments_group/default_incremental_interest', '1'),
('default', 0, 'mundipagg_config/installments_group/visa_status', '1'),
('default', 0, 'mundipagg_config/installments_group/mastercard_status', '1'),
('default', 0, 'mundipagg_config/installments_group/hipercard_status', '1'),
('default', 0, 'mundipagg_config/installments_group/diners_status', '1'),
('default', 0, 'mundipagg_config/installments_group/amex_status', '1'),
('default', 0, 'mundipagg_config/installments_group/elo_status', '1'),
('default', 0, 'mundipagg_config/multibuyer_group/multibuyer_status', '1');

UPDATE magento.core_config_data SET value = '1' WHERE path = 'dev/log/active';
UPDATE magento.core_config_data SET value = 'system.log' WHERE path = 'dev/log/file';
UPDATE magento.core_config_data SET value = 'exception.log' WHERE path = 'dev/log/exception_file';
UPDATE magento.core_config_data SET value = '4' WHERE path = 'customer/address/street_lines';
UPDATE magento.core_config_data SET value = '1' WHERE path = 'customer/create_account/vat_frontend_visibility';
UPDATE magento.core_config_data SET value = 'req' WHERE path = 'customer/address/taxvat_show';
UPDATE magento.core_config_data SET value = '1' WHERE path = 'system/smtp/disable';
UPDATE magento.customer_eav_attribute set multiline_count = 4 where attribute_id = 25;
UPDATE magento.customer_eav_attribute set is_visible = 4 where attribute_id = 15;
UPDATE magento.core_config_data SET value = '0' WHERE path LIKE 'carriers/%/active' AND path NOT LIKE 'carriers/freeshipping/active' AND path NOT LIKE 'carriers/flatrate/active';

UPDATE magento.cataloginventory_stock_item SET qty = 30000 WHERE product_id = 337;

-- creating user



