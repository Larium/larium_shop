<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

use Larium\Shop\Sale\Cart;
use Larium\Shop\Core\Command\CommandHandler;
use Larium\Shop\Sale\Cart\Event\ItemAddedEvent;
use Larium\Shop\Sale\Repository\OrderRepositoryInterface;
use Larium\Shop\Catalog\Repository\VariantRepositoryInterface;

class CartAddItemHandler extends CommandHandler
{
    private $variantRepository;

    private $orderRepository;

    public function __construct(
        VariantRepositoryInterface $variantRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->variantRepository = $variantRepository;
        $this->orderRepository = $orderRepository;
    }

    public function handle(CartAddItemCommand $command)
    {
        if (null == $orderable = $this->variantRepository->getOneBySku($command->sku)) {
            throw new \InvalidArgumentException(
                sprintf('Could not find product with id `%s`', $command->sku)
            );
        }

        $cart = $this->getCart($command->orderNumber);
        $orderItem = $cart->addItem($orderable, $command->quantity);

        $event = new ItemAddedEvent($cart, $orderItem);
        $this->getEventProvider()->raise($event);

        return $cart;
    }

    private function getCart($orderNumber)
    {
        if ($order = $this->orderRepository->getOneByNumber($orderNumber)) {
            return new Cart($order);
        }

        return new Cart();
    }
}
