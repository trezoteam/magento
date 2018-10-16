Feature: Create order with credit card

  Background:
    Given a new session
    And I define failure screenshot dir as "./failureScreenshots"

  @javascript
  Scenario: I log in with the fixed user to save creditcards in
    Given I pre-register the fixed user
    And I fill address for the fixed user

  @javascript
  Scenario Outline: Buying a product with credit card
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I register on Checkout
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 90 seconds

    Examples:
      | payment_method_code |
      | paymentmodule_creditcard |

  @javascript
  Scenario Outline: Buying a product with credit card and using multi-buyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I register on Checkout
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for text "Fill other buyer data" to appear, for 120 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index_1 |
      | paymentmodule_creditcard | creditcard | 1           |

  @javascript
  Scenario Outline: A guest buying a product with credit card
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 90 seconds

    Examples:
      | payment_method_code |
      | paymentmodule_creditcard |

  @javascript
  Scenario Outline: A guest buying a product with credit card and using multi-buyer
    Given I add a Aviator Sunglasses to cart and go to checkout
    And I checkout as guest
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for text "Fill other buyer data" to appear, for 120 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index_1 |
      | paymentmodule_creditcard | creditcard | 1           |

  @javascript
  Scenario Outline: Create account and buying a product with credit card
    Given I log in with the fixed user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I use jquery to click on element "#<payment_method_code>_creditcard_1_mundicheckout-save-credit-card"
    And I wait for 15 seconds
    And I select "6x of $50,00 with 1% of interest , Total: $303,00" from "<payment_method_code>_creditcard_1_mundicheckout-creditCard-installments"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I should see "Juros de parcelas"
    And I should see "$3.00"
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 90 seconds

    Examples:
      | payment_method_code |
      | paymentmodule_creditcard |

  @javascript
  Scenario Outline: Create account and buying a product with credit card and using multi-buyer
    Given I log in with the fixed user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I wait for 15 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "<payment_method_code>_<form_type_1>_<element_index_1>_mundicheckout-creditCard-installments"
    And I wait for text "Fill other buyer data" to appear, for 120 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 120 seconds

    Examples:
      | payment_method_code | form_type_1 | element_index_1 |
      | paymentmodule_creditcard | creditcard | 1           |

  @javascript
  Scenario Outline: Create account and buying a product with a saved credit card with interest
    Given I log in with the fixed user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I select "Visa xxxx-xxxx-xxxx-6377" from "<payment_method_code>_creditcard_1_mundicheckout-SavedCreditCard"
    And I wait for 15 seconds
    And I select "6x of $50,00 with 1% of interest , Total: $303,00" from "<payment_method_code>_creditcard_1_mundicheckout-creditCard-installments"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I wait for 5 seconds
    And I should see "Juros de parcelas"
    And I should see "$3.00"
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 90 seconds

    Examples:
      | payment_method_code |
      | paymentmodule_creditcard |