<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Command;

use Larium\Shop\Sale\Repository\OrderRepositoryInterface;

class CartRemoveItemHandler
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(CartRemoveItemCommand $command)
    {

    }
}
