@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process SupportedMethods

    @api
    Scenario: Request with good password and SupportedMethods action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "SupportedMethods"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            ["FileVersion","OrderAdd","OrderUpdate","ProductsPrices","ProductsQuantity","ProductsCategories",
            "ProductsData","ProductsList","StatusesList","SupportedMethods"]
            """
        Then I get a "200" response
