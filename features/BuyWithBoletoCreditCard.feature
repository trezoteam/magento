Feature: Create order with boleto and credit card

  Background:
    Given a new session

  @javascript
  Scenario: I log in with the fixed user to save creditcards in
    Given I pre-register the fixed user
    And I fill address for the fixed user

  @javascript
  Scenario Outline: As a guest, I buy a product with boleto and creditcard
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto + Creditcard" to appear, for 120 seconds
    And I click in element "#p_method_<payment_method_code>"
    # Fill Boleto info
    And I wait for 1 seconds
    And I fill in "<payment_method_code>_boleto_1_value" with "100"
    # Fill Second Credit Card Data
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $200,00 without interest , Total: $200,00" from "<payment_method_code>_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for 5 seconds
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 1 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "$ 100.00 payment." to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 120 seconds
    Examples:
      | payment_method_code |
      | paymentmodule_boletocc |

  @javascript
  Scenario Outline: As a guest, I buy a product with boleto multibuyer and creditcard
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto + Creditcard" to appear, for 120 seconds
    And I click in element "#p_method_<payment_method_code>"
    # Fill Boleto info
    And I wait for 1 seconds
    And I wait for text "Fill other buyer data" to appear, for 40 seconds
    And I fill the first multibuyer form inputs
    And I fill in "<payment_method_code>_boleto_1_value" with "100"
    # Fill Second Credit Card Data
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $200,00 without interest , Total: $200,00" from "<payment_method_code>_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for 5 seconds
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 1 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "$ 100.00 payment." to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index_1 |
      | paymentmodule_boletocc | boleto | 1 |

  @javascript
  Scenario Outline: As a guest, I buy a product with boleto and creditcard multibuyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto + Creditcard" to appear, for 120 seconds
    And I click in element "#p_method_<payment_method_code>"
    # Fill Boleto info
    And I wait for 1 seconds
    And I fill in "<payment_method_code>_boleto_1_value" with "100"
    # Fill Second Credit Card Data
    And I fill first credit card data
    And I wait for text "Fill other buyer data" to appear, for 40 seconds
    And I fill the first multibuyer form inputs
    And I wait for 10 seconds
    And I select "1x of $200,00 without interest , Total: $200,00" from "<payment_method_code>_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for 5 seconds
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 1 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "$ 100.00 payment." to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index_1 |
      | paymentmodule_boletocc | creditcard | 1 |