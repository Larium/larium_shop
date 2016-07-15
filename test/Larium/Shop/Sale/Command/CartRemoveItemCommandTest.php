<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Command;

use Larium\Shop\Sale\Repository\InMemoryOrderRepository;

class CartRemoveItemCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $identifier = 'PRD-01-0001';
        $orderNumber = '1234-ABCD';

        $command = new CartRemoveItemCommand($identifier, $orderNumber);

        $orderRepository = new InMemoryOrderRepository();
        $commandHandler = new CartRemoveItemHandler($orderRepository);

        $cart = $commandHandler->handle($command);

        $this->assertEquals(0, $cart->getItems()->count());
    }
}
