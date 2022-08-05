<?php

/**
 * @author Marcin Hubert <hubert.m.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Model;

use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Symfony\Component\Validator\Constraints as Assert;

class OrderAddModel extends AbstractDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected string $email; // test123@test.com
    protected ?string $previous_order_id = null; //
    protected ?int $baselinker_id = null; //
    protected ?string $delivery_fullname = null; // Jan Kowalski
    protected ?string $delivery_company = null; // Firma sp.z.o.o.
    protected ?string $delivery_address = null; // Ul. Przykładowa 12/1
    protected ?string $delivery_postcode = null; // 12-123
    protected ?string $delivery_city = null; // Warszawa
    protected ?string $delivery_state_code = null; //
    protected ?string $delivery_country = null; // Polska
    protected ?string $delivery_country_code = null; // PL
    protected ?string $invoice_fullname = null; // Adam Nowak
    protected ?string $invoice_company = null; // Firma sp.z.o.o.
    protected ?string $invoice_nip = null; //
    protected ?string $invoice_address = null; // Ul. Zakładowa 5
    protected ?string $invoice_postcode = null; // 12-321
    protected ?string $invoice_city = null; // Warszawa
    protected ?string $invoice_state_code = null; //
    protected ?string $invoice_country = null; // Polska
    protected ?string $invoice_country_code = null; // PL
    protected ?string $phone = null; // 48 123 234 345
    protected ?string $delivery_method = null; // Przesyłka pocztowa priorytetowa
    protected ?string $delivery_method_id = null; //
    protected ?float $delivery_price = null; // 12.00
    protected ?string $delivery_point_name = null; //
    protected ?string $delivery_point_pni = null; //
    protected ?string $delivery_point_address = null; //
    protected ?string $delivery_point_postcode = null; //
    protected ?string $delivery_point_city = null; //
    protected ?string $payment_method = null; //
    protected ?bool $payment_method_cod = null; //
    protected ?string $user_comments = null; // przykładowy komentarz użytkownika
    protected ?string $status_id = null; // 1
    protected ?string $payment_method_id = null; //
    protected ?string $currency = null; // PLN
    protected ?bool $want_invoice = null; //
    protected ?bool $paid = null; //
    protected ?bool $change_products_quantity = null; //
    /**
     * @Assert\NotBlank
     * @Assert\Count(min="1")
     * @Assert\Valid
     */
    protected ?array $products = null; // [{"id":1,"name":"testowy przedmiot 1","price":100,"quantity":2},{"id":2,"name":"testowy przedmiot 2","price":150,"quantity":1,"attributes":[{"name":"kolor","value":"niebieski","price":0},{"name":"rozmiar","value":"XXL","price":20}]}]
    protected ?string $transaction_id = null; //
    protected ?string $service_account = null; //
    protected ?string $client_login = null; //

    public function __construct(Input $input)
    {
        $instance = $this;
        $this->customHandlers['products'] = function ($key, $value) use ($instance) {
            $instance->mapProducts($value);
        };

        parent::__construct($input);
    }

    protected function mapProducts(string $value): void
    {
        $data = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        $productsList = [];
        foreach ($data as $productData) {
            $input = new Input($productData);
            $productsList[] = new ProductModel($input);
        }
        $this->products = $productsList;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPreviousOrderId(): ?string
    {
        return $this->previous_order_id;
    }

    public function getBaselinkerId(): ?int
    {
        return $this->baselinker_id;
    }

    public function getDeliveryFullname(): ?string
    {
        return $this->delivery_fullname;
    }

    public function getDeliveryCompany(): ?string
    {
        return $this->delivery_company;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->delivery_address;
    }

    public function getDeliveryPostcode(): ?string
    {
        return $this->delivery_postcode;
    }

    public function getDeliveryCity(): ?string
    {
        return $this->delivery_city;
    }

    public function getDeliveryStateCode(): ?string
    {
        return $this->delivery_state_code;
    }

    public function getDeliveryCountry(): ?string
    {
        return $this->delivery_country;
    }

    public function getDeliveryCountryCode(): ?string
    {
        return $this->delivery_country_code;
    }

    public function getInvoiceFullname(): ?string
    {
        return $this->invoice_fullname;
    }

    public function getInvoiceCompany(): ?string
    {
        return $this->invoice_company;
    }

    public function getInvoiceNip(): ?string
    {
        return $this->invoice_nip;
    }

    public function getInvoiceAddress(): ?string
    {
        return $this->invoice_address;
    }

    public function getInvoicePostcode(): ?string
    {
        return $this->invoice_postcode;
    }

    public function getInvoiceCity(): ?string
    {
        return $this->invoice_city;
    }

    public function getInvoiceStateCode(): ?string
    {
        return $this->invoice_state_code;
    }

    public function getInvoiceCountry(): ?string
    {
        return $this->invoice_country;
    }

    public function getInvoiceCountryCode(): ?string
    {
        return $this->invoice_country_code;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getDeliveryMethod(): ?string
    {
        return $this->delivery_method;
    }

    public function getDeliveryMethodId(): ?string
    {
        return $this->delivery_method_id;
    }

    public function getDeliveryPrice(): ?float
    {
        return $this->delivery_price;
    }

    public function getDeliveryPointName(): ?string
    {
        return $this->delivery_point_name;
    }

    public function getDeliveryPointPni(): ?string
    {
        return $this->delivery_point_pni;
    }

    public function getDeliveryPointAddress(): ?string
    {
        return $this->delivery_point_address;
    }

    public function getDeliveryPointPostcode(): ?string
    {
        return $this->delivery_point_postcode;
    }

    public function getDeliveryPointCity(): ?string
    {
        return $this->delivery_point_city;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function getPaymentMethodCod(): ?bool
    {
        return $this->payment_method_cod;
    }

    public function getUserComments(): ?string
    {
        return $this->user_comments;
    }

    public function getStatusId(): ?string
    {
        return $this->status_id;
    }

    public function getPaymentMethodId(): ?string
    {
        return $this->payment_method_id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getWantInvoice(): ?bool
    {
        return $this->want_invoice;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function getChangeProductsQuantity(): ?bool
    {
        return $this->change_products_quantity;
    }

    /**
     * @return ProductModel[]
     */
    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function getServiceAccount(): ?string
    {
        return $this->service_account;
    }

    public function getClientLogin(): ?string
    {
        return $this->client_login;
    }
}
