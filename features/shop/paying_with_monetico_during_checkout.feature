@paying_with_monetico_session_checkout_during_checkout
Feature: Paying with Monetico during checkout
  In order to buy products
  As a Customer
  I want to be able to pay with "Monetico" payment gateway

  Background:
    Given the store operates on a single channel in "United States"
    And there is a user "john@example.com" identified by "password123"
    And the store has a payment method "Monetico" with a code "monetico" and Monetico payment gateway
    And the store has a product "PHP T-Shirt" priced at "€19.99"
    And the store ships everywhere for free
    And I am logged in as "john@example.com"

  @ui
  Scenario: Successful payment in Monetico
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Monetico" payment method
    When I confirm my order with Monetico payment
    And I get redirected to Monetico and complete my payment
    Then I should be notified that my payment has been completed

  @ui
  Scenario: Never pay on Monetico and click on "go back"
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Monetico" payment method
    When I confirm my order with Monetico payment
    And I click on "go back" during my Monetico payment
    Then I should be notified that my payment has been cancelled
    And I should be able to pay again

  @ui
  Scenario: Retrying the payment with success
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Monetico" payment method
    And I have confirmed my order with Monetico payment
    And I have clicked on "go back" during my Monetico payment
    When I try to pay again Monetico payment
    And I get redirected to Monetico and complete my payment
    Then I should be notified that my payment has been completed
    And I should see the thank you page

  @ui
  Scenario: Retrying the payment and and click on "go back"
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Monetico" payment method
    And I have confirmed my order with Monetico payment
    And I have clicked on "go back" during my Monetico payment
    When I try to pay again Monetico payment
    And I click on "go back" during my Monetico payment
    Then I should be notified that my payment has been cancelled
    And I should be able to pay again