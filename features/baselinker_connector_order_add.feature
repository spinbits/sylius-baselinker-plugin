@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to create order

    @api
    Scenario: Request with good password and OrderAdd action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "OrderAdd",
              "delivery_fullname": "Józef Kępiński",
              "delivery_company": "",
              "delivery_address": "Ulicowa 8",
              "delivery_city": "Miastowo",
              "delivery_country": "Poland",
              "delivery_country_code": "PL",
              "delivery_postcode": "23-763",
              "delivery_point_name": "",
              "invoice_fullname": "Józef Kępiński",
              "invoice_company": "",
              "invoice_address": "Ulicowa 3A",
              "invoice_city": "Miastowo",
              "invoice_country": "Poland",
              "invoice_country_code": "PL",
              "invoice_postcode": "23-763",
              "invoice_nip": "",
              "phone": "+48508803568",
              "email": "m3ivm7qc04+39ca437b9@niepodam.pl",
              "user_comments": "[Allegro: ziomekkxl] ",
              "delivery_method": "Przesyłka elektroniczna (e-mail)",
              "want_invoice": "0",
              "paid": "1",
              "currency": "PLN",
              "delivery_price": "0.00",
              "baselinker_id": "49473472",
              "products": "[{\"id\":\"3\",\"variant_id\":\"3\",\"sku\":\"europa-universalis-iv-digital-extreme-edition-stea\",\"name\":\"Europa Universalis IV Extreme Edition Steam\",\"price\":\"16.80\",\"quantity\":\"1\",\"auction_id\":\"10629760154\"}]",
              "payment_method": "Przelewy24",
              "payment_method_cod": "0",
              "client_login": "ziomekkxl",
              "service_account": "17806",
              "transaction_id": "5053d4f1-f62b-11eb-a213-7dfad2f46a8c",
              "change_products_quantity": "1"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body with same structure:
            """
            {
            "order_id": 21
            }
            """
        Then I get a "200" response
