Feature: deleting a todo

As a user
I want to be able to delete individual todos

Scenario: deleting a single todo
  Given I have a done todo "An important thing"
  And I am on "/"
  When I click ".todo-delete-0"
  Then I should see 0 "#todos li" elements after waiting
