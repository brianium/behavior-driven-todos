Feature: marking todos as complete

As a user
I want to be able to mark todos complete

Scenario: viewing a done todo
  Given I have a done todo "Get groceries"
  When I am on "/"
  Then I should see 1 ".todo-complete" element after waiting

Scenario: marking a single todo as done
  Given I have a todo "Get groceries"
  And I am on "/"
  When I check "todo_0"
  Then I should see 1 ".todo-complete" element after waiting
