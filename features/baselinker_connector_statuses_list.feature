@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process Statuses List

    @api
    Scenario: Request with good password and Statuses List action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "StatusesList"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            {
            "cart": "Koszyk - w trakcie zamawiania",
            "completed": "Ukończone"
            }
            """
        Then I get a "200" response
