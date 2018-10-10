Feature: Create order with boleto

  Background:
    Given a new session

  @javascript
  Scenario: Create account and buying using boleto
    Given I pre-register my user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I fill in billing address info
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 15 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds


  @javascript
  Scenario Outline: Create account and buying using boleto with multi-buyer
    Given I pre-register my user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I fill in billing address info
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 120 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 40 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index 1 |
      | paymentmodule_boleto | boleto | 1                   |

 @javascript
  Scenario: Buying a product with boleto
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I register on Checkout
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 45 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 45 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds

  @javascript
  Scenario Outline: Buying a product with boleto with muilt-buyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I register on Checkout
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 120 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index 1 |
      | paymentmodule_boleto | boleto | 1                   |

  @javascript
  Scenario: A guest buying a product with boleto
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 120 seconds

  @javascript
  Scenario Outline: A guest buying a product with boleto and using a multibuyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 120 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 120 seconds
    And I wait for element "#mundipagg-checkout-success-order-info a" to appear, for 120 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Multibuyer Teste" to appear, for 120 seconds

  Examples:
    | payment_method_code | form_type_1 | element_index 1 |
    | paymentmodule_boleto | boleto | 1                   |