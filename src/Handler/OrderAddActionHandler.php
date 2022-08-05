<?php

/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\SyliusBaselinkerPlugin\Handler;

use Spinbits\SyliusBaselinkerPlugin\Model\OrderAddModel;
use Spinbits\SyliusBaselinkerPlugin\Entity\Order\Order;
use Spinbits\SyliusBaselinkerPlugin\Service\OrderCreateService;
use Spinbits\SyliusBaselinkerPlugin\Handler\HandlerInterface;
use Spinbits\SyliusBaselinkerPlugin\Rest\Exception\InvalidArgumentException;
use Spinbits\SyliusBaselinkerPlugin\Rest\Input;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderAddActionHandler implements HandlerInterface
{
    private ValidatorInterface $validator;
    private OrderCreateService $orderCreateService;

    public function __construct(ValidatorInterface $validator, OrderCreateService $orderCreateService)
    {
        $this->validator = $validator;
        $this->orderCreateService = $orderCreateService;
    }

    /**
     * @param Input $input
     * @return array
     * @throws InvalidArgumentException
     */
    public function handle(Input $input): array
    {
        $orderAddModel = new OrderAddModel($input);

        $result = $this->validator->validate($orderAddModel);
        $this->assertIsValid($result);

        /** @var Order $order */
        $order = $this->orderCreateService->createOrder($orderAddModel);
        $order->setBaselinkerOrderId((string) $orderAddModel->getBaselinkerId());

        return ['order_id' => $order->getId()];
    }

    /**
     * @param ConstraintViolationListInterface $result
     *
     * @throws InvalidArgumentException
     */
    private function assertIsValid(ConstraintViolationListInterface $result): void
    {
        if (count($result) < 1) {
            return;
        }

        /** @var ConstraintViolation[] $result */
        $errors = [];
        foreach ($result as $violation) {
            $errors[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
        }

        throw new InvalidArgumentException('validation failed: ' . implode("; ", $errors));
    }
}
