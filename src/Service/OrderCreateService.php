<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Service;

use Spinbits\BaselinkerSdk\Model\OrderAddModel;
use Spinbits\BaselinkerSdk\Model\ProductModel;
use Sylius\Component\Core\Model\Address;
use SM\Factory\Factory as StateMachineFactory;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductVariantRepository;
use Sylius\Component\Channel\Context\CachedPerRequestChannelContext;
use Sylius\Component\Core\Cart\Modifier\LimitingOrderItemQuantityModifier;
use Sylius\Component\Core\Currency\Context\ChannelAwareCurrencyContext;
use Sylius\Component\Core\Factory\CartItemFactory;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Locale\Context\CompositeLocaleContext;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\CompositeOrderProcessor;
use Sylius\Component\Payment\Factory\PaymentFactory;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Resource\Factory\Factory;
use Webmozart\Assert\Assert;

class OrderCreateService
{
    private Factory $orderFactory;
    private CartItemFactory $orderItemFactory;
    private Factory $customerFactory;
    private CustomerRepository $customerRepository;
    private OrderRepository $orderRepository;
    private ProductVariantRepository $productVariantRepository;
    private CompositeLocaleContext $localeContext;
    private ChannelAwareCurrencyContext $currencyContext;
    private LimitingOrderItemQuantityModifier $orderItemQuantityModifier;
    private CompositeOrderProcessor $orderProcessor;
    private CachedPerRequestChannelContext $channelContext;
    private StateMachineFactory $stateMachineFactory;
    private PaymentFactory $paymentFactory;
    private PaymentMethodRepositoryInterface $paymentMethodRepository;
    private ?string $paymentMethodCode = null;

    public function __construct(
        Factory $orderFactory,
        CartItemFactory $orderItemFactory,
        Factory $customerFactory,
        PaymentFactory $paymentFactory,
        CustomerRepository $customerRepository,
        OrderRepository $orderRepository,
        ProductVariantRepository $productVariantRepository,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        CompositeLocaleContext $localeContext,
        ChannelAwareCurrencyContext $currencyContext,
        CachedPerRequestChannelContext $channelContext,
        LimitingOrderItemQuantityModifier $orderItemQuantityModifier,
        CompositeOrderProcessor $orderProcessor,
        StateMachineFactory $stateMachineFactory,
        ?string $paymentMethodCode
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderItemFactory = $orderItemFactory;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->localeContext = $localeContext;
        $this->currencyContext = $currencyContext;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->orderProcessor = $orderProcessor;
        $this->customerFactory = $customerFactory;
        $this->channelContext = $channelContext;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->paymentFactory = $paymentFactory;
        $this->paymentMethodRepository = $paymentMethodRepository;

        if (null === $paymentMethodCode) {
            $this->paymentMethodCode = $this->getDefaultPaymentMethodCode();
        }
    }

    public function createOrder(OrderAddModel $inputData): OrderInterface
    {
        Assert::notNull($inputData->getBaselinkerId(), sprintf("BaselinkerId can not be empty."));

        /* @var $order OrderInterface */
        $order = $this->orderRepository->findOneBy(['externalOrderId' => $inputData->getBaselinkerId()]);
        if (null === $order) {
            $order = $this->orderFactory->createNew();

            $order->setExternalOrderId((string) $inputData->getBaselinkerId());
            $order->markExternal();
            $order->fillBaseLinkerMetadata($inputData->getCurrency());

            $customer = $this->getCustomer($inputData);
            $address = $this->getAddress($inputData, $customer);
            $payment = $this->getPayment($this->paymentMethodCode, $order->getCurrencyCode());

            $order->setCustomer($customer);
            $order->setChannel($this->channelContext->getChannel());
            $order->setLocaleCode($this->localeContext->getLocaleCode());
            $order->setCurrencyCode($this->currencyContext->getCurrencyCode());

            $order->setShippingAddress($address);
            $order->setBillingAddress(clone $address);

            foreach ($inputData->getProducts() as $product) {
                $orderItem = $this->getOrderItem($product);
                $order->addItem($orderItem);
            }
            $this->orderProcessor->process($order);

            $order->addPayment($payment);
            $this->orderRepository->add($order);
            $this->checkout($order);
        }

        $this->markPayment($order, (bool) $inputData->getPaid());
        $this->orderRepository->add($order);

        return $order;
    }

    private function getCustomer(OrderAddModel $inputData): CustomerInterface
    {
        $customer = $this->customerRepository->findOneBy(['email' => $inputData->getEmail()]);
        if (null === $customer) {
            $customer = $this->customerFactory->createNew();
            /* @var $customer CustomerInterface */
            $customer->setEmail($inputData->getEmail());
            $customer->setPhoneNumber($inputData->getPhone());
        }

        return $customer;
    }

    private function getAddress(OrderAddModel $inputData, CustomerInterface $customer): Address
    {
        $address = new Address();
        $address->setFirstName($inputData->getDeliveryFullname() ?? "");
        $address->setLastName("");
        $address->setPostcode("");
        $address->setCustomer($customer);
        $address->setPhoneNumber($inputData->getPhone() ?? "");
        $address->setStreet($inputData->getDeliveryAddress() ?? "");
        $address->setCity($inputData->getDeliveryCity() ?? "");
        $address->setCountryCode($inputData->getDeliveryCountryCode() ?? "");

        return $address;
    }

    private function getOrderItem(ProductModel $product): OrderItemInterface
    {
        /** @var ProductVariantInterface|null $variant */
        $variant = $this->productVariantRepository->find($product->getVariantId());
        $message = sprintf("Product variant with %s id was not found!", $product->getVariantId());
        Assert::isInstanceOf($variant, ProductVariantInterface::class, $message);

        $orderItem = $this->orderItemFactory->createNew();
        $orderItem->setVariant($variant);
        $this->orderItemQuantityModifier->modify($orderItem, $product->getQuantity());

        return $orderItem;
    }

    private function getPayment(string $paymentMethodCode, string $currencyCode ): PaymentInterface
    {
        $paymentMethod = $this->paymentMethodRepository->findOneBy(['code' => $paymentMethodCode]);
        $payment = $this->paymentFactory->createNew();
        $payment->setMethod($paymentMethod);
        $payment->setCurrencyCode($currencyCode);

        return $payment;
    }

    private function checkout(OrderInterface $order): void
    {
        $stateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);
        $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_ADDRESS);
        if ($stateMachine->can(OrderCheckoutTransitions::TRANSITION_COMPLETE)) {
            $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE);
        }
    }

    private function markPayment(OrderInterface $order, bool $paid): void {
        $paymentStateMachine = $this->stateMachineFactory->get($order->getLastPayment(), PaymentTransitions::GRAPH);
        if ($paid && $order->getLastPayment()->getState() !== PaymentInterface::STATE_COMPLETED) {
            if ($paymentStateMachine->can(PaymentTransitions::TRANSITION_COMPLETE)) {
                $paymentStateMachine->apply(PaymentTransitions::TRANSITION_COMPLETE);
            }
        }
    }

    private function getDefaultPaymentMethodCode(): string
    {
        /** @var PaymentMethod[] $paymentMethods */
        $paymentMethods = $this->paymentMethodRepository->findAll();
        return $paymentMethods[0]->getCode();
    }
}
