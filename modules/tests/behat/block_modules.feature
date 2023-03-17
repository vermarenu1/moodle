Feature: Block modules
  As a Moodle user
  I want to see a list of modules in a course
  So that I can easily access them

  Scenario: View modules in a course
    Given I am logged in as a "Admin"
    And I am on the course page
    When I view the "Modules" block
    Then I should see a table of modules with their IDs, names, creation dates, and statuses
