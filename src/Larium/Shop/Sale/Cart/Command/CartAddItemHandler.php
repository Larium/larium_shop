<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

use Larium\Shop\Sale\Cart;
use Larium\Shop\Sale\Repository\OrderRepositoryInterface;
use Larium\Shop\Catalog\Repository\VariantRepositoryInterface;

class CartAddItemHandler
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
        $orderable = $this->variantRepository->getOneBySku($command->sku);

        $cart = $this->getCart($command->orderNumber);
        $cart->addItem($orderable, $command->quantity);

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
