Feature: Visitor
  In order to have control the content view and send update alerts
  As a site Admininstrator
  I need to be able to create and manage visitors

Scenario: Add a new Visitor
  Given I fill a visitor email, first name, last name and groups with:
    |email|first_name|last_name|groups|
    |test@mail.com|first|last|test|
  When I create a Visitor
  Then a new visitor shold be added
