Feature:
  In order to encourage students to rate their classes
  As a student
  I want to see a rating popup

  Scenario: Not showing user a popup after they attended to 4 classes
    Given there is a user "Stefano"
    And "Stefano" attended to 4 classes
    When I ask if the popup should be showed to "Stefano"
    Then I see that they should not see a popup

  Scenario: Not showing user a popup after they attended to 5 classes and 24 + 1 hours have not passed
    Given there is a user "Stefano"
    And "Stefano" attended to 5 classes
    And 24 hours has passed
    When I ask if the popup should be showed to "Stefano"
    Then I see that they should not see a popup

  Scenario: Showing user a popup after they attended to 5 classes and 24 + 1 hours have passed
    Given there is a user "Stefano"
    And "Stefano" attended to 5 classes
    And 25 hours has passed
    When I ask if the popup should be showed to "Stefano"
    Then I see that they should see a popup

  Scenario: Not showing user a popup after they dismiss a popup
    Given there is a user "Stefano"
    And "Stefano" attended to 5 classes
    And 25 hours has passed
    When "Stefano" dismisses their popup
    And I ask if the popup should be showed to "Stefano"
    Then I see that they should not see a popup

  Scenario: Not showing user a popup after they rate their classes
    Given there is a user "Stefano"
    And "Stefano" attended to 5 classes
    And 25 hours has passed
    When "Stefano" rates their classes
    And I ask if the popup should be showed to "Stefano"
    Then I see that they should not see a popup

