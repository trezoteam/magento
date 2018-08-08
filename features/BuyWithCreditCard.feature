Feature: Create order with boleto
  @javascript
  Scenario: Buying a product with boleto
    Given I am on "/index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 20 seconds
    And I click in element ".btn-proceed-checkout"
    And I wait for text "Register and Checkout" to appear, for 20 seconds
    And I click in element "#checkout-step-login div div ul li:last-child input"
    And I click in element "#onepage-guest-register-button"
    And I fill in "billing:firstname" with "Teste"
    And I fill in "billing:lastname" with "Teste"
    And I fill in "billing:email" with a random email
    And I fill in "billing:street1" with "Teste Rua"
    And I fill in "billing:street2" with "Teste número"
    And I fill in "billing:street3" with "Teste complemento"
    And I fill in "billing:street4" with "Teste Bairro"
    And I fill in "billing:city" with "Teste Cidade"
    And I select "Brazil" from "billing:country_id"
    And I select "Rio de Janeiro" from "billing[region_id]"
    And I fill in "billing:postcode" with "200000000"
    And I fill in "billing:telephone" with "2125222222"
    And I fill in "billing:taxvat" with "67632474277"
    And I fill in "billing:customer_password" with "test123"
    And I fill in "billing:confirm_password" with "test123"
    And I use jquery to click on element "#billing-buttons-container button"
    And I wait for text "Flat" to appear, for 15 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 15 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 10 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 10 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 15 seconds

#    And I click in element "#p_method_paymentmodule_creditcard"
#    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-number" with "4916318338556377"
#    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-holdername" with "Teste Teste"
#    And I select "01" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expmonth"
#    And I select "2025" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expyear"
#    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-cvv" with "123"
#    And I click in element "#paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
#    And I click in element "#paymentmodule_creditcard_creditcard_1_mundicheckout-cvv"
#    And I wait for text "1x de R$105,00 sem juros , Total: R$105,00" to appear, for 10 seconds
#    And I select "1x de R$105,00 sem juros , Total: R$105,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expy