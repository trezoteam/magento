Feature: Partial Scenarios

  #Add sunglasses to card
  @javascript @smartStep
  Scenario: I add a Aviator Sunglasses to cart and go to checkout
    Given I am on "/index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 30 seconds
    And I click in element ".btn-proceed-checkout"

  #fill in billing address info
  @javascript @smartStep
  Scenario: I fill in billing address info
    Given I fill in "billing:street1" with "Rua"
    And I fill in "billing:street2" with "número"
    And I fill in "billing:street3" with "complemento"
    And I fill in "billing:street4" with "Bairro"
    And I fill in "billing:city" with "Cidade"
    And I select "Brazil" from "billing:country_id"
    And I select "Rio de Janeiro" from "billing[region_id]"
    And I fill in "billing:postcode" with "200000000"
    And I fill in "billing:vat_id" with "08944984441"
    And I fill in "billing:telephone" with "2125222222"

  #pre register user
  @javascript @smartStep
  Scenario: I pre-register my user
    Given I am on "index.php/customer/account/login/"
    Then I wait for text "LOGIN OR CREATE AN ACCOUNT" to appear, for 5 seconds
    And I click in element ".new-users .buttons-set a"
    Then I should be on "index.php/customer/account/create/"
    And I fill in "firstname" with "Pre-registered"
    And I fill in "lastname" with "Pre-registered"
    And I fill in "email" with a random email
    And I fill in "taxvat" with "67632474277"
    And I fill in "password" with "test123"
    And I fill in "confirmation" with "test123"
    And I click in element ".buttons-set button"
    Then I should be on "index.php/customer/account/index/"

  #pre register fixed user
  @javascript @smartStep
  Scenario: I pre-register the fixed user
    Given I am on "index.php/customer/account/login/"
    Then I wait for text "LOGIN OR CREATE AN ACCOUNT" to appear, for 5 seconds
    And I click in element ".new-users .buttons-set a"
    Then I should be on "index.php/customer/account/create/"
    And I fill in "firstname" with "Fixed"
    And I fill in "lastname" with "Fixed"
    And I fill in "email" with the fixed email
    And I fill in "taxvat" with "67632474277"
    And I fill in "password" with "test123"
    And I fill in "confirmation" with "test123"
    And I click in element ".buttons-set button"
    Then I should see "MY DASHBOARD" appear

  #fill address to the fixed user
  @javascript @smartStep
  Scenario: I fill address for the fixed user
    Given I am on "index.php/customer/address/new/"
    Then I wait for text "ADD NEW ADDRESS" to appear, for 5 seconds
    And I fill in "street_1" with "Ruaf"
    And I fill in "street_2" with "númerof"
    And I fill in "street_3" with "complementof"
    And I fill in "street_4" with "Bairrof"
    And I fill in "city" with "Cidadef"
    And I select "Brazil" from "country_id"
    And I select "Rio de Janeiro" from "region_id"
    And I fill in "postcode" with "200000000"
    And I fill in "telephone" with "2125222222"
    And I press "Save Address"
    Then I should see "The address has been saved"

  #log with fixed user
  @javascript @smartStep
  Scenario: I log in with the fixed user
    Given I am on "index.php/customer/account/login/"
    Then I wait for text "LOGIN OR CREATE AN ACCOUNT" to appear, for 5 seconds
    And I fill in "email" with the fixed email
    And I fill in "pass" with "test123"
    And I click in element "#send2"
    And I wait for 1 seconds
    Then I should see "MY DASHBOARD" appear

  #register on checkout
  @javascript @smartStep
  Scenario: I register on Checkout
    Given I wait for text "Register and Checkout" to appear, for 30 seconds
    And I click in element "#checkout-step-login div div ul li:last-child input"
    And I click in element "#onepage-guest-register-button"
    And I fill in "billing:firstname" with "Register"
    And I fill in "billing:lastname" with "Register"
    And I fill in "billing:email" with a random email
    And I fill in billing address info
    And I fill in "billing:taxvat" with "67632474277"
    And I fill in "billing:customer_password" with "test123"
    And I fill in "billing:confirm_password" with "test123"
    And I use jquery to click on element "#billing-buttons-container button"

  #guest checkout
  @javascript @smartStep
  Scenario: I checkout as guest
    Given I wait for text "Checkout as Guest" to appear, for 30 seconds
    And I click in element "#checkout-step-login div div ul li:first-child input"
    And I click in element "#onepage-guest-register-button"
    And I fill in "billing:firstname" with "Guest"
    And I fill in "billing:lastname" with "Guest"
    And I fill in "billing:email" with a random email
    And I fill in billing address info
    And I fill in "billing:taxvat" with "67632474277"
    And I use jquery to click on element "#billing-buttons-container button"

  #select Flat shipping type
  @javascript @smartStep
  Scenario: I select 'Flat' shipping method
    Given I wait for text "Flat" to appear, for 45 seconds
    And I use jquery to click on element "#s_method_flatrate_flatrate"
    And I use jquery to click on element "#shipping-method-buttons-container button"

  #fill first multibuyer
  @javascript @smartStep
  Scenario: I fill the first multibuyer form inputs
    Given I click in element "#<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_name" with "Multibuyer One Teste"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_email" with a random email
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_phone" with "212533333"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_taxvat" with "52419830660"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_zip_code" with "200000000"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_street" with "Multibuyer One Rua"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_number" with "23"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_complement" with "Multibuyer One Complemento"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_neighborhood" with "Multibuyer One Bairro"
    And I fill in "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_city" with "Multibuyer One Cidade"
    And I select "Brazil" from "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "<payment_method_code>_<form_type_1>_<element_index_1>_multi_buyer_state"
    
  #fill second multibuyer
  @javascript @smartStep
  Scenario: I fill the second multibuyer form inputs
    Given I click in element "#<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_enabled"
    And I wait for 5 seconds
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_name" with "Multibuyer  Two Teste"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_email" with a random email
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_phone" with "212533333"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_taxvat" with "52419830660"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_zip_code" with "200000000"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_street" with "Multibuyer Two Rua"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_number" with "23"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_complement" with "Multibuyer Two Complemento"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_neighborhood" with "Multibuyer Two Bairro"
    And I fill in "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_city" with "Multibuyer Two Cidade"
    And I select "Brazil" from "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_country"
    And I wait for 2 seconds
    And I select "Rio de Janeiro" from "<payment_method_code>_<form_type_2>_<element_index_2>_multi_buyer_state"

  #fill first credit card Data
  @javascript @smartStep
  Scenario: I fill first credit card data
    And If "<payment_method_code>_creditcard_1_mundicheckout-SavedCreditCard" is present, I select "Fill Data" from it
    And I fill in "<payment_method_code>_creditcard_1_mundicheckout-number" with "4916318338556377"
    And I fill in "<payment_method_code>_creditcard_1_mundicheckout-holdername" with "first Teste Teste"
    And I select "01" from "<payment_method_code>_creditcard_1_mundicheckout-expmonth"
    And I select "2025" from "<payment_method_code>_creditcard_1_mundicheckout-expyear"
    And I fill in "<payment_method_code>_creditcard_1_mundicheckout-cvv" with "123"
    And I click in element "#<payment_method_code>_creditcard_1_mundicheckout-cvv"

  #fill second credit card Data
  @javascript @smartStep
  Scenario: I fill second credit card data
    And If "<payment_method_code>_creditcard_2_mundicheckout-SavedCreditCard" is present, I select "Fill Data" from it
    And I fill in "<payment_method_code>_creditcard_2_mundicheckout-number" with "4916318338556377"
    And I fill in "<payment_method_code>_creditcard_2_mundicheckout-holdername" with "second Teste Teste"
    And I select "01" from "<payment_method_code>_creditcard_2_mundicheckout-expmonth"
    And I select "2025" from "<payment_method_code>_creditcard_2_mundicheckout-expyear"
    And I fill in "<payment_method_code>_creditcard_2_mundicheckout-cvv" with "123"
    And I click in element "#<payment_method_code>_creditcard_2_mundicheckout-cvv"
