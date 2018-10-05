Feature: Partial Scenarios

  #Add sunglasses to card
  @javascript @smartStep
  Scenario: I add a Aviator Sunglasses to cart and go to checkout
    Given I am on "/index.php/aviator-sunglasses.html"
    Then I wait for text "Gunmetal" to appear, for 15 seconds
    And I submit the form with id "#product_addtocart_form"
    And I wait for text "SHOPPING CART" to appear, for 20 seconds
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

  #register on checkout
  @javascript @smartStep
  Scenario: I register on Checkout
    Given I wait for text "Register and Checkout" to appear, for 20 seconds
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
    Given I wait for text "Checkout as Guest" to appear, for 20 seconds
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

  @javascript @smartStep
  Scenario: I fill the first multibuyer form inputs
    Given I click in element "#<payment_method_code>_<form_type_1>_1_multi_buyer_enabled"
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