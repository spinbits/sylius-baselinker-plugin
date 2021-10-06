@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to update order

    @api
    Scenario: Request with good password and OrderUpdate action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "OrderUpdate",
              "orders_ids": "20,21",
              "update_type": "status",
              "update_value": "sent"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            {
                "counter": 2
            }
            """
        Then I get a "200" response
