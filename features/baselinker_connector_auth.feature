@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process request with password and action

    @api
    Scenario: Request without password
        Given I have the payload:
            """
            {
              "no_bl_pass_field": "no-pass",
              "no_action_field" : "no-action"
            }
            """
        When I request "POST /baselinker-connector"
        Then I get response body:
            """
            {
              "error": true,
              "error_code": 422,
              "error_text": "Missing password parameter"
            }
            """
        And I get a "422" response

    Scenario: Request with wrong password
        Given I have the payload:
            """
            {
              "bl_pass": "ObjectOrienter",
              "no_action_field" : "2"
            }
            """
        When I request "POST /baselinker-connector"
        Then I get a "401" response
        And I get response body:
            """
            {
              "error": true,
              "error_code": 401,
              "error_text": "Wrong password"
            }
            """

    Scenario: Request with good password and missing action field
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "no_action_field" : "2"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            {
              "error": true,
              "error_code": 422,
              "error_text": "Missing action parameter"
            }
            """
        Then I get a "422" response

    Scenario: Request with good password and unsupported action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "NotExistingAction"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            {
              "error": true,
              "error_code": 422,
              "error_text": "Handler for action \"NotExistingAction\" is not configured. Please use \"setHandler\" to map it."
            }
            """
        Then I get a "422" response
