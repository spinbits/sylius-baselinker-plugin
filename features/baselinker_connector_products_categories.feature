@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process ProductsCategories

    @api
    Scenario: Request with good password and ProductsCategories action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "ProductsCategories"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body:
            """
            {
               "MENU_CATEGORY":"Category",
               "t_shirts":"Category \/ T-shirts",
               "mens_t_shirts":"Category \/ T-shirts \/ Men",
               "womens_t_shirts":"Category \/ T-shirts \/ Women",
               "caps":"Category \/ Caps",
               "simple_caps":"Category \/ Caps \/ Simple",
               "caps_with_pompons":"Category \/ Caps \/ With pompons",
               "dresses":"Category \/ Dresses",
               "jeans":"Category \/ Jeans",
               "mens_jeans":"Category \/ Jeans \/ Men",
               "womens_jeans":"Category \/ Jeans \/ Women"
            }
            """
        Then I get a "200" response
