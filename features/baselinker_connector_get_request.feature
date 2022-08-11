@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to support GET request as expected by BL

    @api
    Scenario: GET request will return specific response
        Given I have the payload:
            """
            """
        When I request "GET /baselinker-connector"
        And I get response body:
            """
            {"error":true,"error_code":"no_password","error_text":"Wrong request"}
            """
        Then I get a "200" response
