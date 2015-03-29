Feature: adding a todo

As a user
I want my todos to be persisted
So I don't have to retype them

Scenario: adding a todo
  Given I am on "/"
  When I fill in "todo" with "Get groceries"
  And I press "add"
  Then I should see "Get groceries"

Scenario: revisiting added todos
  Given I am on "/"
  When I fill in "todo" with "Pick up dinner"
  And I press "add"
  And I reload the page
  Then I should see "Pick up dinner"

Scenario: adding a duplicate todo
  Given I have a done todo "Pick up dinner"
  And I am on "/"
  When I fill in "todo" with "Pick up dinner"
  And I press "add"
  Then I should see "Todo already exists" after waiting
  And I should see 1 "#todos li" elements
