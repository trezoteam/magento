Feature: Visa Credit Card Order

  Scenario: Buying a product with boleto
    Given I am on "/index.php/catalog/product/view/id/1"
    Then I wait for text "ADD TO CART" to appear, for 10 seconds
    And I click in element ".btn-cart"
    And I wait for text "PROCEED TO CHECKOUT" to appear, for 10 seconds
    And I click in element ".btn-proceed-checkout"
    And I wait for text "Register and Checkout" to appear, for 10 seconds
    And I click in element "#checkout-step-login div div ul li:last-child input"
    And I click in element "#onepage-guest-register-button"
    When I fill in "billing:firstname" with "Teste"
    And I fill in "billing:lastname" with "Teste"
    And I fill in "billing:email" with a random email
    And I fill in "billing:street1" with "Teste Rua"
    And I fill in "billing:street2" with "Teste número"
    And I fill in "billing:street3" with "Teste complemento"
    And I fill in "billing:street4" with "Teste Bairro"
    And I fill in "billing:city" with "Teste Cidade"
    And I select "Brasil" from "billing:country_id"
    And I select "Rio de Janeiro" from "billing[region_id]"
    And I fill in "billing:postcode" with "200000000"
    And I fill in "billing:telephone" with "2125222222"
    And I fill in "billing:taxvat" with "67632474277"
    And I fill in "billing:customer_password" with "test123"
    And I fill in "billing:confirm_password" with "test123"
    And I click in element "#billing-buttons-container button"
    And I wait for text "Flat Rate" to appear, for 5 seconds
    And I click in element "#s_method_flatrate_flatrate"
    And I click in element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Credit Card" to appear, for 5 seconds
    And I click in element "#p_method_paymentmodule_boleto"
    And I click in element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 10 seconds
    And I click in element "#review-buttons-container button"
    And I wait for text "Clique aqui para" to appear, for 10 seconds
    And I click in element "#mundipagg-checkout-success-order-info a"
    And document should open in new tab
    Then I wait for text "Instruções de Impressão" to appear, for 10 seconds
#    And I click in element "#p_method_paymentmodule_creditcard"
#    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-number" with "4916318338556377"
#    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-holdername" with "Teste Teste"
#    And I select "01" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expmonth"
#    And I select "2025" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expyear"
#    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-cvv" with "123"
#    And I click in element "#paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
#    And I click in element "#paymentmodule_creditcard_creditcard_1_mundicheckout-cvv"
#    And I wait for text "1x de R$105,00 sem juros , Total: R$105,00" to appear, for 10 seconds
#    And I select "1x de R$105,00 sem juros , Total: R$105,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expyear"
