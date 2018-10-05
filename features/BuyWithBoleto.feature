Feature: Create order with boleto

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
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds

  @javascript
  Scenario Outline: Buying a product with boleto with muilt-buyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I register on Checkout
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear

    And I click in element "#<payment_method_code>_<form_type_1>_1_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_name" with "Multibuyer Teste"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_email" with a random email
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_phone" with "212533333"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_taxvat" with "52419830660"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_zip_code" with "200000000"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_street" with "Multibuyer Rua"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_number" with "23"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_complement" with "Multibuyer Complemento"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_neighborhood" with "Multibuyer Bairro"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_city" with "Multibuyer Cidade"
    And I select "Brazil" from "<payment_method_code>_<form_type_1>_1_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "<payment_method_code>_<form_type_1>_1_multi_buyer_state"

    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear

    Examples:
      | payment_method_code | form_type_1 |
      | paymentmodule_boleto | boleto |

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
    And I wait for text "Click here to" to appear
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear

  @javascript
  Scenario Outline: A guest buying a product with boleto and using a multibuyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear

    And I fill the first multibuyer form inputs

    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Multibuyer Teste" to appear

  Examples:
  | payment_method_code | form_type_1 |
  | paymentmodule_boleto | boleto |

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
    And I wait for text "Click here to" to appear
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds


  @javascript
  Scenario Outline: Create account and buying using boleto with multi-buyer
    Given I pre-register my user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I fill in billing address info
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Boleto" to appear
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 40 seconds
    And I click in element "#<payment_method_code>_<form_type_1>_1_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_name" with "Multibuyer Teste"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_email" with a random email
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_phone" with "212533333"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_taxvat" with "52419830660"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_zip_code" with "200000000"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_street" with "Multibuyer Rua"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_number" with "23"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_complement" with "Multibuyer Complemento"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_neighborhood" with "Multibuyer Bairro"
    And I fill in "<payment_method_code>_<form_type_1>_1_multi_buyer_city" with "Multibuyer Cidade"
    And I select "Brazil" from "<payment_method_code>_<form_type_1>_1_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "<payment_method_code>_<form_type_1>_1_multi_buyer_state"
    And I use jquery to click on element "#payment-buttons-container button"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear

    Examples:
      | payment_method_code | form_type_1 |
      | paymentmodule_boleto | boleto |