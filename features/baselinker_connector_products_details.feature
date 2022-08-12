@baselinker_connector
Feature: Programmer
    In order to provide baselinker integration
    As Programmer
    I want to process Products Details

    @api
    Scenario: Request with good password and Products Details action
        Given I have the payload:
            """
            {
              "bl_pass": "example-password-change-it",
              "action" : "ProductsData",
              "products_id": "18,19"
            }
            """
        When I request "POST /baselinker-connector"
        And I get response body with same structure:
            """
            {
               "18":{
                  "sku":"727F_patched_cropped_jeans",
                  "name":"727F patched cropped jeans",
                  "tax":0,
                  "description":"Dicta nobis aut numquam sapiente omnis. Officia esse rem illo animi rerum voluptas eum. Qui repellendus dolores cupiditate perferendis officiis nesciunt sit est. Perferendis non exercitationem ut aspernatur.\n\nOmnis velit fuga accusamus ipsa alias dignissimos. Dolores rerum aut est quam nobis. Amet dignissimos laudantium repellat.\n\nEst modi repudiandae nihil voluptas omnis necessitatibus dicta. Beatae tempore saepe rerum mollitia eum. Cupiditate sequi alias quos repellendus dolores ea.",
                  "categoryId":"womens_jeans",
                  "images":[
                     "http:\/\/127.0.0.1:8080\/media\/cache\/resolve\/sylius_admin_product_original\/e4\/33\/11945322abd9d3260239dfb5d41f.jpg"
                  ],
                  "variants":{
                     "100":{
                        "full_name":"S",
                        "name":"S",
                        "price":33.87,
                        "quantity":1,
                        "sku":"727F_patched_cropped_jeans-variant-0"
                     },
                     "101":{
                        "full_name":"M",
                        "name":"M",
                        "price":70.25,
                        "quantity":2,
                        "sku":"727F_patched_cropped_jeans-variant-1"
                     },
                     "102":{
                        "full_name":"L",
                        "name":"L",
                        "price":87.85,
                        "quantity":2,
                        "sku":"727F_patched_cropped_jeans-variant-2"
                     },
                     "103":{
                        "full_name":"XL",
                        "name":"XL",
                        "price":49.64,
                        "quantity":9,
                        "sku":"727F_patched_cropped_jeans-variant-3"
                     },
                     "104":{
                        "full_name":"XXL",
                        "name":"XXL",
                        "price":48.82,
                        "quantity":3,
                        "sku":"727F_patched_cropped_jeans-variant-4"
                     }
                  },
                  "features":[
                     [
                        "Jeans brand",
                        "You are breathtaking"
                     ],
                     [
                        "Jeans collection",
                        "Sylius Winter 2019"
                     ],
                     [
                        "Jeans material",
                        "100% jeans"
                     ]
                  ],
                  "allCategories":[
                     "Jeans",
                     "Women"
                  ],
                  "allCategoriesExpanded":[
                     "Category \/ Jeans",
                     "Category \/ Jeans \/ Women"
                  ],
                  "shortDescription":"Enim autem numquam quaerat consequuntur ut sit. Deleniti at deserunt quas deserunt eos aut. Magnam sequi sapiente accusamus voluptate. Molestiae beatae est quos quo nihil.",
                  "slug":"727f-patched-cropped-jeans",
                  "url":"\/en_US\/products\/727f-patched-cropped-jeans"
               },
               "19":{
                  "sku":"111F_patched_jeans_with_fancy_badges",
                  "name":"111F patched jeans with fancy badges",
                  "tax":0,
                  "description":"Fuga exercitationem officia sapiente mollitia. Consequatur dolorem debitis sed minus. Magnam eveniet inventore nemo laboriosam numquam. Sit repellendus placeat ratione neque nesciunt. Autem debitis et nihil.\n\nRerum quisquam maiores omnis ab vitae. Ullam soluta vero quibusdam aspernatur. Fuga nobis vitae consequatur non aut.\n\nAliquid quam aspernatur voluptatem nihil consequatur. Vel quisquam eum error natus eveniet sed nemo. Corporis magni provident sint magni rerum quis. Cumque blanditiis alias aut exercitationem cupiditate facilis quisquam enim. Vel est doloribus vero ea.",
                  "categoryId":"womens_jeans",
                  "images":[
                     "http:\/\/127.0.0.1:8080\/media\/cache\/resolve\/sylius_admin_product_original\/44\/b5\/5d289c924d471819d969cf5c0079.jpg"
                  ],
                  "variants":{
                     "105":{
                        "full_name":"S",
                        "name":"S",
                        "price":92.88,
                        "quantity":4,
                        "sku":"111F_patched_jeans_with_fancy_badges-variant-0"
                     },
                     "106":{
                        "full_name":"M",
                        "name":"M",
                        "price":29.07,
                        "quantity":1,
                        "sku":"111F_patched_jeans_with_fancy_badges-variant-1"
                     },
                     "107":{
                        "full_name":"L",
                        "name":"L",
                        "price":55.62,
                        "quantity":2,
                        "sku":"111F_patched_jeans_with_fancy_badges-variant-2"
                     },
                     "108":{
                        "full_name":"XL",
                        "name":"XL",
                        "price":34.59,
                        "quantity":4,
                        "sku":"111F_patched_jeans_with_fancy_badges-variant-3"
                     },
                     "109":{
                        "full_name":"XXL",
                        "name":"XXL",
                        "price":45.17,
                        "quantity":6,
                        "sku":"111F_patched_jeans_with_fancy_badges-variant-4"
                     }
                  },
                  "features":[
                     [
                        "Jeans brand",
                        "You are breathtaking"
                     ],
                     [
                        "Jeans collection",
                        "Sylius Winter 2019"
                     ],
                     [
                        "Jeans material",
                        "100% jeans"
                     ]
                  ],
                  "allCategories":[
                     "Jeans",
                     "Women"
                  ],
                  "allCategoriesExpanded":[
                     "Category \/ Jeans",
                     "Category \/ Jeans \/ Women"
                  ],
                  "shortDescription":"Asperiores inventore aliquid explicabo et. Nostrum adipisci aspernatur in libero veniam ipsa quia. Numquam quae consequatur culpa at distinctio quae omnis.",
                  "slug":"111f-patched-jeans-with-fancy-badges",
                  "url":"\/en_US\/products\/111f-patched-jeans-with-fancy-badges"
               },
               "pages":1
            }
            """
        Then I get a "200" response
