Feature: initial visit to the todo app

As a user
I want to input todos
So I can track my business

Scenario: visiting todos for the first time
  Given I am on "/"
  Then I should see "Todos"
  And I should see a "#todo" element
