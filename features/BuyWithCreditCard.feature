Feature: Create order with credit card
  @javascript
  Scenario: Buying a product with credit card
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
    And I wait for text "Flat" to appear
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-number" with "4916318338556377"
    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-holdername" with "Teste Teste"
    And I select "01" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expmonth"
    And I select "2025" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expyear"
    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-cvv" with "123"
    And I click in element "#paymentmodule_creditcard_creditcard_1_mundicheckout-cvv"
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear, for 90 seconds

  @javascript
  Scenario: Buying a product with credit card and using multi-buyer
    Given I am on "/index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear
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
    And I wait for text "Flat" to appear
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Credit Card" to appear, for 15 seconds
    And I click in element "#p_method_paymentmodule_creditcard"
    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-number" with "4916318338556377"
    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-holdername" with "Teste Teste"
    And I select "01" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expmonth"
    And I select "2025" from "paymentmodule_creditcard_creditcard_1_mundicheckout-expyear"
    And I fill in "paymentmodule_creditcard_creditcard_1_mundicheckout-cvv" with "123"
    And I click in element "#paymentmodule_creditcard_creditcard_1_mundicheckout-cvv"
    And I wait for 10 seconds
    And I select "1x of $300,00 without interest , Total: $300,00" from "paymentmodule_creditcard_creditcard_1_mundicheckout-creditCard-installments"
    And I wait for text "Fill other buyer data" to appear
    And I click in element "#paymentmodule_creditcard_creditcard_1_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_name" with "Multibuyer Teste"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_email" with a random email
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_phone" with "212533333"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_taxvat" with "52419830660"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_zip_code" with "200000000"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_street" with "Multibuyer Rua"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_number" with "23"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_complement" with "Multibuyer Complemento"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_neighborhood" with "Multibuyer Bairro"
    And I fill in "paymentmodule_creditcard_creditcard_1_multi_buyer_city" with "Multibuyer Cidade"
    And I select "Brazil" from "paymentmodule_boleto_boleto_1_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "paymentmodule_boleto_boleto_1_multi_buyer_state"
    And I use jquery to click on element "#payment-buttons-container button"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for 20 seconds
    And I wait for text "Grand Total" to appear, for 90 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "THANK YOU FOR YOUR PURCHASE!" to appear