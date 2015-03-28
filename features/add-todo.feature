Feature: adding a todo

As a user
I want my todos to be persisted
So I don't have to retype them

Scenario: adding a todo
  Given I am on "/"
  When I fill in "todo" with "Get groceries"
  And I reload the page
  Then I should see "Get groceries"
