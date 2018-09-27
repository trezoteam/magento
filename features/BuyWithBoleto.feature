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
    And I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 45 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 45 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds

  @javascript
  Scenario: Buying a product with boleto with muilt-buyer
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
    And I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 45 seconds
    And I click in element "#paymentmodule_boleto_boleto_1_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_name" with "Multibuyer Teste"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_email" with a random email
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_phone" with "212533333"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_taxvat" with "67632474288"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_zip_code" with "200000000"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_street" with "Multibuyer Rua"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_number" with "Multibuyer Numero"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_complement" with "Multibuyer Complemento"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_neighborhood" with "Multibuyer Bairro"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_city" with "Multibuyer Cidade"
    And I select "Brazil" from "paymentmodule_boleto_boleto_1_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "paymentmodule_boleto_boleto_1_multi_buyer_state"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 45 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 45 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds

  @javascript
  Scenario: A guest buying a product with boleto
    Given I am on "/index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 20 seconds
    And I click in element ".btn-proceed-checkout"
    And I wait for text "Checkout as Guest" to appear, for 20 seconds
    And I click in element "#checkout-step-login div div ul li:first-child input"
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
    And I use jquery to click on element "#billing-buttons-container button"
    And I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 45 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 45 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds

  @javascript
  Scenario: A guest buying a product with boleto and using a multibuyer
    Given I am on "/index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 20 seconds
    And I click in element ".btn-proceed-checkout"
    And I wait for text "Checkout as Guest" to appear, for 20 seconds
    And I click in element "#checkout-step-login div div ul li:first-child input"
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
    And I use jquery to click on element "#billing-buttons-container button"
    And I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 45 seconds
    And I click in element "#paymentmodule_boleto_boleto_1_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_name" with "Multibuyer Teste"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_email" with a random email
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_phone" with "212533333"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_taxvat" with "67632474288"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_zip_code" with "200000000"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_street" with "Multibuyer Rua"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_number" with "Multibuyer Numero"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_complement" with "Multibuyer Complemento"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_neighborhood" with "Multibuyer Bairro"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_city" with "Multibuyer Cidade"
    And I select "Brazil" from "paymentmodule_boleto_boleto_1_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "paymentmodule_boleto_boleto_1_multi_buyer_state"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 60 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 50 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Multibuyer Teste" to appear, for 50 seconds

  @javascript
  Scenario: Create account and buying using boleto
    Given I am on "index.php/customer/account/login/"
    Then I wait for text "LOGIN OR CREATE AN ACCOUNT" to appear, for 5 seconds
    And I click in element ".new-users .buttons-set a"
    Then I should be on "index.php/customer/account/create/"
    And I fill in "firstname" with "Teste"
    And I fill in "lastname" with "Teste"
    And I fill in "email" with a random email
    And I fill in "taxvat" with "67632474277"
    And I fill in "password" with "test123"
    And I fill in "confirmation" with "test123"
    And I click in element ".buttons-set button"
    Then I should be on "index.php/customer/account/index/"
    When I go to "index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 20 seconds
    And I click in element ".btn-proceed-checkout"
    And I fill in "billing:street1" with "Teste Rua"
    And I fill in "billing:street2" with "Teste número"
    And I fill in "billing:street3" with "Teste complemento"
    And I fill in "billing:street4" with "Teste Bairro"
    And I fill in "billing:city" with "Teste Cidade"
    And I select "Brazil" from "billing:country_id"
    And I select "Rio de Janeiro" from "billing[region_id]"
    And I fill in "billing:postcode" with "200000000"
    And I fill in "billing:telephone" with "2125222222"
    And I use jquery to click on element "#billing-buttons-container button"
    And I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 15 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 60 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 40 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds


  @javascript
  Scenario: Create account and buying using boleto with multi-buyer
    Given I am on "index.php/customer/account/login/"
    Then I wait for text "LOGIN OR CREATE AN ACCOUNT" to appear, for 5 seconds
    And I click in element ".new-users .buttons-set a"
    Then I should be on "index.php/customer/account/create/"
    And I fill in "firstname" with "Teste"
    And I fill in "lastname" with "Teste"
    And I fill in "email" with a random email
    And I fill in "taxvat" with "67632474277"
    And I fill in "password" with "test123"
    And I fill in "confirmation" with "test123"
    And I click in element ".buttons-set button"
    Then I should be on "index.php/customer/account/index/"
    When I go to "index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 20 seconds
    And I click in element ".btn-proceed-checkout"
    And I fill in "billing:street1" with "Teste Rua"
    And I fill in "billing:street2" with "Teste número"
    And I fill in "billing:street3" with "Teste complemento"
    And I fill in "billing:street4" with "Teste Bairro"
    And I fill in "billing:city" with "Teste Cidade"
    And I select "Brazil" from "billing:country_id"
    And I select "Rio de Janeiro" from "billing[region_id]"
    And I fill in "billing:postcode" with "200000000"
    And I fill in "billing:telephone" with "2125222222"
    And I use jquery to click on element "#billing-buttons-container button"
    And I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"
    And I wait for text "Mundipagg Boleto" to appear, for 45 seconds
    And I use jquery to click on element "#p_method_paymentmodule_boleto"
    And I wait for text "Fill other buyer data" to appear, for 40 seconds
    And I click in element "#paymentmodule_boleto_boleto_1_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_name" with "Multibuyer Teste"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_email" with a random email
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_phone" with "212533333"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_taxvat" with "67632474288"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_zip_code" with "200000000"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_street" with "Multibuyer Rua"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_number" with "Multibuyer Numero"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_complement" with "Multibuyer Complemento"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_neighborhood" with "Multibuyer Bairro"
    And I fill in "paymentmodule_boleto_boleto_1_multi_buyer_city" with "Multibuyer Cidade"
    And I select "Brazil" from "paymentmodule_boleto_boleto_1_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "paymentmodule_boleto_boleto_1_multi_buyer_state"
    And I use jquery to click on element "#payment-buttons-container button"
    And I wait for text "PLACE ORDER" to appear, for 65 seconds
    And I use jquery to click on element "#review-buttons-container button"
    And I wait for text "Click here to" to appear, for 50 seconds
    And I follow the element "#mundipagg-checkout-success-order-info a" href
    Then I wait for text "Instru" to appear, for 45 seconds