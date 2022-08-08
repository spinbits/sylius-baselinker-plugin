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

class OrderAddModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected string $email; // test123@test.com
    private string $previous_order_id; //
    private int $baselinker_id; //
    private string $delivery_fullname; // Jan Kowalski
    private string $delivery_company; // Firma sp.z.o.o.
    private string $delivery_address; // Ul. Przykładowa 12/1
    private string $delivery_postcode; // 12-123
    private string $delivery_city; // Warszawa
    private string $delivery_state_code; //
    private string $delivery_country; // Polska
    private string $delivery_country_code; // PL
    private string $invoice_fullname; // Adam Nowak
    private string $invoice_company; // Firma sp.z.o.o.
    private string $invoice_nip; //
    private string $invoice_address; // Ul. Zakładowa 5
    private string $invoice_postcode; // 12-321
    private string $invoice_city; // Warszawa
    private string $invoice_state_code; //
    private string $invoice_country; // Polska
    private string $invoice_country_code; // PL
    private string $phone; // 48 123 234 345
    private string $delivery_method; // Przesyłka pocztowa priorytetowa
    private string $delivery_method_id; //
    private float $delivery_price; // 12.00
    private string $delivery_point_name; //
    private string $delivery_point_pni; //
    private string $delivery_point_address; //
    private string $delivery_point_postcode; //
    private string $delivery_point_city; //
    private string $payment_method; //
    private bool $payment_method_cod; //
    private string $user_comments; // przykładowy komentarz użytkownika
    private string $status_id; // 1
    private string $payment_method_id; //
    private string $currency; // PLN
    private bool $want_invoice; //
    private bool $paid; //
    private bool $change_products_quantity; //
    /**
     * @Assert\NotBlank
     * @Assert\Count(min="1")
     * @Assert\Valid
     */
    protected array $products; // [{"id":1,"name":"testowy przedmiot 1","price":100,"quantity":2},{"id":2,"name":"testowy przedmiot 2","price":150,"quantity":1,"attributes":[{"name":"kolor","value":"niebieski","price":0},{"name":"rozmiar","value":"XXL","price":20}]}]
    private string $transaction_id; //
    private string $service_account; //
    private string $client_login; //

    public function __construct(Input $input)
    {
        $this->email = (string) $input->get('email');
        $this->previous_order_id = (string) $input->get('previous_order_id');
        $this->baselinker_id = (int) $input->get('baselinker_id');

        $this->delivery_fullname = (string) $input->get('delivery_fullname');
        $this->delivery_company = (string) $input->get('delivery_company');
        $this->delivery_address = (string) $input->get('delivery_address');
        $this->delivery_postcode = (string) $input->get('delivery_postcode');
        $this->delivery_city = (string) $input->get('delivery_city');
        $this->delivery_state_code = (string) $input->get('delivery_state_code');
        $this->delivery_country = (string) $input->get('delivery_country');
        $this->delivery_country_code = (string) $input->get('delivery_country_code');

        $this->invoice_fullname = (string) $input->get('invoice_fullname');
        $this->invoice_company = (string) $input->get('invoice_company');
        $this->invoice_nip = (string) $input->get('invoice_nip');
        $this->invoice_address = (string) $input->get('invoice_address');
        $this->invoice_postcode = (string) $input->get('invoice_postcode');
        $this->invoice_city = (string) $input->get('invoice_city');
        $this->invoice_state_code = (string) $input->get('invoice_state_code');
        $this->invoice_country = (string) $input->get('invoice_country');
        $this->invoice_country_code = (string) $input->get('invoice_country_code');

        $this->phone = (string) $input->get('phone');
        $this->delivery_method = (string) $input->get('delivery_method');
        $this->delivery_method_id = (string) $input->get('delivery_method_id');
        $this->delivery_price = (float) $input->get('delivery_price');

        $this->delivery_point_name = (string) $input->get('delivery_point_name');
        $this->delivery_point_pni = (string) $input->get('delivery_point_pni');
        $this->delivery_point_address = (string) $input->get('delivery_point_address');
        $this->delivery_point_city = (string) $input->get('delivery_point_city');
        $this->delivery_point_postcode = (string) $input->get('delivery_point_postcode');

        $this->payment_method = (string) $input->get('payment_method');
        $this->payment_method_id = (string) $input->get('payment_method_id');
        $this->payment_method_cod = (bool) $input->get('payment_method_cod');

        $this->user_comments = (string) $input->get('user_comments');
        $this->status_id = (string) $input->get('status_id');

        $this->currency = (string) $input->get('currency');
        $this->want_invoice = (bool) $input->get('want_invoice');
        $this->paid = (bool) $input->get('paid');
        $this->change_products_quantity = (bool) $input->get('change_products_quantity');

        $this->transaction_id = (string) $input->get('transaction_id');
        $this->service_account = (string) $input->get('service_account');
        $this->client_login = (string) $input->get('client_login');

        $this->products = $this->decodeProducts($input->get('products'));
    }

    /**
     * @param string $value
     * @return array|ProductModel[]
     * @throws \JsonException
     */
    private function decodeProducts(string $value): array
    {
        $data = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        $productsList = [];
        foreach ($data as $productData) {
            $input = new Input($productData);
            $productsList[] = new ProductModel($input);
        }

        return $productsList;
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
