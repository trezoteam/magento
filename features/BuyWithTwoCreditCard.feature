Feature: Create order with two credit card

  Background:
    Given a new session

  @javascript
  Scenario Outline: Buying a product with two credit card
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I register on Checkout
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Two Credit Cards" to appear, for 120 seconds

    And I click in element "#p_method_paymentmodule_twocreditcards"

    # Fill First Credit Card Data
    And I wait for 1 seconds
    And I fill in "paymentmodule_twocreditcards_creditcard_1_value" with "100"
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $100,00 without interest , Total: $100,00" from "paymentmodule_twocreditcards_creditcard_1_mundicheckout-creditCard-installments"

    # Fill Second Credit Card Data
    And I fill second credit card data
    And I wait for 10 seconds
    And I select "1x of $200,00 without interest , Total: $200,00" from "paymentmodule_twocreditcards_creditcard_2_mundicheckout-creditCard-installments"
    And I wait for 5 seconds

#    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 1 seconds
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 50 seconds
    And I wait for 2 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 120 seconds

    Examples:
      | payment_method_code |
      | paymentmodule_twocreditcards|