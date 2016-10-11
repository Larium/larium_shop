<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Command\Cart;

use Larium\Shop\Sale\Repository\InMemoryOrderRepository;
use Larium\Shop\Catalog\Repository\InMemoryVariantRepository;

class CartAddItemCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleCommand()
    {
        $sku = 'PRD-01-0001';
        $quantity = 1;
        $command = new CartAddItemCommand($sku, $quantity);

        $variantRepository = new InMemoryVariantRepository();
        $orderRepository = new InMemoryOrderRepository();
        $commandHandler = new CartAddItemHandler($variantRepository, $orderRepository);

        $cart = $commandHandler->handle($command);

        $this->assertInstanceOf('Larium\Shop\Sale\Cart', $cart);

        $this->assertEquals($quantity, $cart->getTotalQuantity());
    }
}
