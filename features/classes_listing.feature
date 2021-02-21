Feature:
    In order to book a class
    As a user
    I want to see a list of available classes

    Scenario: Displaying a list of available classes with their properties
        Given there is a class "Programming 101" starting at "2020-01-01"
        And user "Stefano" attends to class "Programming 101"
        When I open a list of classes
        Then the response should be received
        And I see 1st available class "name" is "Programming 101"
        And I see 1st available class "status" is "scheduled"