Feature: Create order with credit card

  Background:
    Given a new session

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
    And I wait for text "Fill other buyer data" to appear, for 300 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 300 seconds

    Examples:
      | payment_method_code | form_type_1 |
      | paymentmodule_creditcard | creditcard |

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
    And I wait for text "Fill other buyer data" to appear, for 300 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 300 seconds

    Examples:
      | payment_method_code | form_type_1 |
      | paymentmodule_creditcard | creditcard |

  @javascript
  Scenario Outline: Create account and buying a product with credit card
    Given I pre-register my user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I fill in billing address info
    And I use jquery to click on element "#billing-buttons-container button"
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
  Scenario Outline: Create account and buying a product with credit card and using multi-buyer
    Given I pre-register my user
    When I add a Aviator Sunglasses to cart and go to checkout
    And I fill in billing address info
    And I use jquery to click on element "#billing-buttons-container button"
    And I select 'Flat' shipping method
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill first credit card data
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for text "Fill other buyer data" to appear, for 300 seconds
    And I fill the first multibuyer form inputs
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 300 seconds

    Examples:
      | payment_method_code | form_type_1 |
      | paymentmodule_creditcard | creditcard |