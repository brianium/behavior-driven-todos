Feature: viewing the todo input

As a user
I want to input todos
So I can track my business

Scenario: visiting todos for the first time
  Given I have not yet created todos
  When I am on "/"
  Then I should see "Todos"
  And I should see an "#todo" element
