<?php

$installer = $this;

$installer->startSetup();

$prefix = Mage::getConfig()->getTablePrefix();

$installer->run("
CREATE TABLE paymentmodule_savedcreditcard
(
  id                    INT AUTO_INCREMENT
    PRIMARY KEY,
  mundipagg_card_id     VARCHAR(255) NULL,
  holder_name           VARCHAR(255) NULL,
  mundipagg_customer_id VARCHAR(255) NULL,
  brand_name            VARCHAR(12)  NULL,
  first_six_digits      VARCHAR(6)   NULL,
  last_four_digits      VARCHAR(4)   NULL,
  expiration_month      INT(2)       NULL,
  expiration_year       INT(4)       NULL
)
;
");

$installer->endSetup();
