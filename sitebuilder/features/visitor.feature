Feature: Visitor
  In order to have control the content view and send update alerts
  As a site Admininstrator
  I need to be able to create and manage visitors

Scenario: Add a new Visitor
  Given I fill a visitor email, first name, last name and groups with:
    |email|first_name|last_name|groups|
    |test@mail.com|first|last|test|
  When I create a Visitor
  Then a new visitor should be added

Scenario: Add an invalid Visitor
  Given I fill a visitor email, first name, last name and groups with invalid values:
    |email|first_name|last_name|groups|
    |nomail.com||||
  When I create a Visitor
  Then a new visitor should NOT be added

Scenario: Reset a Visitor password
    Given that there is a visitor
    When I reset it's password
    Then the visitor password should be invalidated

Scenario: Remove a Visitor
    Given that there is a visitor
    When I remove this visitor
    Then the visitor should not exist anymore
