@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process Products List

    @api
    Scenario: Request with good password and Products List action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "ProductsList"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body with same structure:
            """
                {
                   "1":{
                      "name":"Everyday white basic T-Shirt",
                      "quantity":0,
                      "price":19.64,
                      "ean":null,
                      "sku":"Everyday_white_basic_T_Shirt"
                   },
                   "2":{
                      "name":"Loose white designer T-Shirt",
                      "quantity":9,
                      "price":82.37,
                      "ean":null,
                      "sku":"Loose_white_designer_T_Shirt"
                   },
                   "3":{
                      "name":"Ribbed copper slim fit Tee",
                      "quantity":4,
                      "price":31.51,
                      "ean":null,
                      "sku":"Ribbed_copper_slim_fit_Tee"
                   },
                   "4":{
                      "name":"Sport basic white T-Shirt",
                      "quantity":4,
                      "price":29.93,
                      "ean":null,
                      "sku":"Sport_basic_white_T_Shirt"
                   },
                   "5":{
                      "name":"Raglan grey & black Tee",
                      "quantity":3,
                      "price":86.36,
                      "ean":null,
                      "sku":"Raglan_grey_&_black_Tee"
                   },
                   "6":{
                      "name":"Oversize white cotton T-Shirt",
                      "quantity":8,
                      "price":74.09,
                      "ean":null,
                      "sku":"Oversize_white_cotton_T_Shirt"
                   },
                   "7":{
                      "name":"Knitted burgundy winter cap",
                      "quantity":3,
                      "price":34.15,
                      "ean":null,
                      "sku":"Knitted_burgundy_winter_cap"
                   },
                   "8":{
                      "name":"Knitted wool-blend green cap",
                      "quantity":4,
                      "price":99.88,
                      "ean":null,
                      "sku":"Knitted_wool_blend_green_cap"
                   },
                   "9":{
                      "name":"Knitted white pompom cap",
                      "quantity":4,
                      "price":43.22,
                      "ean":null,
                      "sku":"Knitted_white_pompom_cap"
                   },
                   "10":{
                      "name":"Cashmere-blend violet beanie",
                      "quantity":7,
                      "price":4.81,
                      "ean":null,
                      "sku":"Cashmere_blend_violet_beanie"
                   },
                   "11":{
                      "name":"Beige strappy summer dress",
                      "quantity":9,
                      "price":42.77,
                      "ean":null,
                      "sku":"Beige_strappy_summer_dress"
                   },
                   "12":{
                      "name":"Off shoulder boho dress",
                      "quantity":0,
                      "price":60.87,
                      "ean":null,
                      "sku":"Off_shoulder_boho_dress"
                   },
                   "13":{
                      "name":"Ruffle wrap festival dress",
                      "quantity":6,
                      "price":58.92,
                      "ean":null,
                      "sku":"Ruffle_wrap_festival_dress"
                   },
                   "14":{
                      "name":"911M regular fit jeans",
                      "quantity":7,
                      "price":20.94,
                      "ean":null,
                      "sku":"911M_regular_fit_jeans"
                   },
                   "15":{
                      "name":"330M slim fit jeans",
                      "quantity":3,
                      "price":74.16,
                      "ean":null,
                      "sku":"330M_slim_fit_jeans"
                   },
                   "16":{
                      "name":"990M regular fit jeans",
                      "quantity":5,
                      "price":12.78,
                      "ean":null,
                      "sku":"990M_regular_fit_jeans"
                   },
                   "17":{
                      "name":"007M black elegance jeans",
                      "quantity":4,
                      "price":99.29,
                      "ean":null,
                      "sku":"007M_black_elegance_jeans"
                   },
                   "18":{
                      "name":"727F patched cropped jeans",
                      "quantity":3,
                      "price":48.82,
                      "ean":null,
                      "sku":"727F_patched_cropped_jeans"
                   },
                   "19":{
                      "name":"111F patched jeans with fancy badges",
                      "quantity":6,
                      "price":45.17,
                      "ean":null,
                      "sku":"111F_patched_jeans_with_fancy_badges"
                   },
                   "20":{
                      "name":"000F office grey jeans",
                      "quantity":2,
                      "price":59.36,
                      "ean":null,
                      "sku":"000F_office_grey_jeans"
                   },
                   "21":{
                      "name":"666F boyfriend jeans with rips",
                      "quantity":6,
                      "price":29.17,
                      "ean":null,
                      "sku":"666F_boyfriend_jeans_with_rips"
                   },
                   "pages":1
                }
            """
        Then I get a "200" response
