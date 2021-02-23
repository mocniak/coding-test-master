Feature:
  In order to encourage students to rate their classes
  As a student
  I want to see a rating popup

  Scenario: Not showing user a popup after they attended to 4 classes
    Given there is a user "Stefano"
    And "Stefano" attended to 4 classes
    When I ask if the popup should be showed to "Stefano"
    Then I see that I should not see a popup

  Scenario: Showing user a popup after they attended to 5 classes
    Given there is a user "Stefano"
    And "Stefano" attended to 4 classes
    When I ask if the popup should be showed to "Stefano"
    Then I see that I should not see a popup

  Scenario: Not showing user a popup after they dismiss a popup
    Given there is a user "Stefano"
    And "Stefano" attended to 5 classes
    When I dismiss the popup
    And I ask if the popup should be showed to "Stefano"
    Then I see that I should not see a popup

  Scenario: Not showing user a popup after they rate their classes
    Given there is a user "Stefano"
    And "Stefano" attended to 5 classes
    When I rated my classes
    And I ask if the popup should be showed to "Stefano"
    Then I see that I should not see a popup

