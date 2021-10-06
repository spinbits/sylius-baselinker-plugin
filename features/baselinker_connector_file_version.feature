@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process FileVersion

    @api
    Scenario: Request with good password and FileVersion action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "FileVersion"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            {
            "platform": "Sylius by spinbits",
            "version": "4.0.0",
            "standard": 4
            }
            """
        Then I get a "200" response
