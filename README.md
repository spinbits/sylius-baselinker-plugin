<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Sylius Baselinker Plugin</h1>

## Help
If you need some help with Sylius development, don't hesitate to contact us directly. You can send us an email at office@spinbits.io

## Quickstart Installation

1. Run `composer require spinbits/sylius-baselinker-plugin`.

2. Import route into your routing file:

```
spinbits_baselinker_plugin:
    resource: "@SpinbitsSyliusBaselinkerPlugin/Resources/config/admin_routing.yml"
```

3. Import trait to your Order entity: `src/Entity/Order/Order.php`
```
use Spinbits\SyliusBaselinkerPlugin\Entity\Order\OrderTrait;

class Order extends BaseOrder
{
    use OrderTrait;

```

4. Register BaselinkerHandlers and passowrd: `config/services.yaml`
```
    Spinbits\BaselinkerSdk\RequestHandler:
        arguments:
            $password: '%env(BASELINKER_PASSWORD)%'
        calls:
            - method: registerHandler
              arguments:
                  - FileVersion
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\FileVersionActionHandler'
            - method: registerHandler
              arguments:
                  - OrderAdd
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\OrderAddActionHandler'
            - method: registerHandler
              arguments:
                  - ProductsPrices
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\ProductPricesActionHandler'
            - method: registerHandler
              arguments:
                  - ProductsQuantity
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\ProductQuantityActionHandler'
            - method: registerHandler
              arguments:
                  - ProductsCategories
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\ProductsCategoriesActionHandler'
            - method: registerHandler
              arguments:
                  - ProductsDetails
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\ProductsDetailsActionHandler'
            - method: registerHandler
              arguments:
                  - ProductsList
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\ProductsListActionHandler'
            - method: registerHandler
              arguments:
                  - StatusesList
                  - '@Spinbits\SyliusBaselinkerPlugin\Handler\StatusesListActionHandler'
            - method: registerHandler
              arguments:
                  - SupportedMethods
                  - '@Spinbits\BaselinkerSdk\Handler\Common\SupportedMethodsActionHandler'

```

5. Copy Migration file:
`cp vendor/spinbits/sylius-baselinker-plugin/src/Migrations/Version20211005090821.php ./src/Migrations/`

6. Run migrations:
`bin/console doctrine:migrations:migrate`

## Usage

### Running plugin tests

  - PHPUnit

    ```bash
    vendor/bin/phpunit
    ```

  - PHPSpec

    ```bash
    vendor/bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    vendor/bin/behat --strict --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. [Install Symfony CLI command](https://symfony.com/download).
 
    2. Start Headless Chrome:
    
      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```
    
    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:
    
      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
      ```
    
    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript"
      ```
    
  - Static Analysis
  
    - Psalm
    
      ```bash
      vendor/bin/psalm
      ```
      
    - PHPStan
    
      ```bash
      vendor/bin/phpstan analyse -c phpstan.neon -l max src/  
      ```

  - Coding Standard
  
    ```bash
    vendor/bin/ecs check src
    ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    (cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=test bin/console server:run -d public)
    ```
    
- Using `dev` environment:

    ```bash
    (cd tests/Application && APP_ENV=dev bin/console sylius:fixtures:load)
    (cd tests/Application && APP_ENV=dev bin/console server:run -d public)
    ```
