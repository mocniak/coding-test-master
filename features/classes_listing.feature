Feature:
    In order to book a class
    As a student
    I want to see a list of available classes

    Scenario: Displaying a list of available classes with their properties
        Given there is a class "Programming 101" starting at "2020-01-01"
        And user "Stefano" attends to class "Programming 101"
        When I open a list of classes
        Then I see 1st available class "topic" is "Programming 101"
        And I see 1st available class "status" is "ended"
        And I see 1st available class "startsAt" is "2020-01-01"
        And I see that "Stefano" is attending to "Programming 101"

    Scenario: Class with no attendees is showed as scheduled
        Given there is a class "Programming 101" starting at "2020-01-01"
        And nobody attends to class "Programming 101"
        When I open a list of classes
        And I see 1st available class "status" is "cancelled"

    Scenario: List of classes doesn't show students' email addresses
        Given there is a class "Programming 101" starting at "2020-01-01"
        And user "Stefano" attends to class "Programming 101"
        When I open a list of classes
        And I don't see user "Stefano"'s email address in "Programming 101" class
