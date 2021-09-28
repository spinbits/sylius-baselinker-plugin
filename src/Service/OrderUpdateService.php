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

use Doctrine\ORM\EntityManagerInterface;
use SM\Factory\Factory as StateMachineFactory;
use Spinbits\BaselinkerSdk\Model\OrderUpdateModel;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\OrderTransitions;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Webmozart\Assert\Assert;

class OrderUpdateService
{
    private OrderRepository $orderRepository;
    private StateMachineFactory $stateMachineFactory;
    private EntityManagerInterface $orderEntityManager;

    public function __construct(
        OrderRepository $orderRepository,
        StateMachineFactory $stateMachineFactory,
        EntityManagerInterface $orderEntityManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->orderEntityManager = $orderEntityManager;
    }

    public function updateOrders(OrderUpdateModel $inputData): array
    {
        $orders = [];
        foreach ($inputData->getOrdersIds() as $orderId) {
            $orders[] = $this->handleExistingOrder($orderId, $inputData);
        }

        return $orders;
    }


    private function handleExistingOrder(string $orderId, OrderUpdateModel $inputData)
    {
        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->find($orderId);
        Assert::isInstanceOf($order, OrderInterface::class, sprintf("Order %s was not found", $orderId));

        switch ($inputData->getUpdateType()) {
            case "paid":
                $this->markPayment($order, (bool) $inputData->getUpdateValue());
                break;
            case "status":
                $this->markOrderStatus($order, $inputData->getUpdateValue());
                break;
            default:
                // do nothing
                break;
        }
        $this->orderEntityManager->flush();
        return $order;
    }


    private function markPayment(OrderInterface $order, bool $paid): void
    {
        $paymentStateMachine = $this->stateMachineFactory->get($order->getLastPayment(), PaymentTransitions::GRAPH);
        if ($paid && $order->getLastPayment()->getState() !== PaymentInterface::STATE_COMPLETED) {
            if ($paymentStateMachine->can(PaymentTransitions::TRANSITION_COMPLETE)) {
                $paymentStateMachine->apply(PaymentTransitions::TRANSITION_COMPLETE);
            }
        }
    }

    private function markOrderStatus(OrderInterface $order, string $updateValue): void
    {
        $orderStateMachine = $this->stateMachineFactory->get($order, OrderTransitions::GRAPH);

        switch ($updateValue) {
            case OrderInterface::STATE_CANCELLED:
                if ($orderStateMachine->can(OrderTransitions::TRANSITION_CANCEL)) {
                    $orderStateMachine->apply(OrderTransitions::TRANSITION_CANCEL);
                }
                break;
            default:
                break;
        }
    }
}
