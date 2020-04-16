@managing_payment_method
Feature: Adding a new Monetico payment method
  In order to allow payment for orders, using the Monetico gateway
  As an Administrator
  I want to add new payment methods to the system

  Background:
    Given the store operates on a channel named "US" in "USD" currency
    And I am logged in as an administrator

  @ui
  Scenario: Adding a new monetico payment method
    Given I want to create a new Monetico payment method
    When I name it "Monetico" in "English (United States)"
    And I specify its code as "monetico"
    And I configure it with test monetico gateway data
    And I add it
    Then I should be notified that it has been successfully created
    And the payment method "Monetico" should appear in the registry