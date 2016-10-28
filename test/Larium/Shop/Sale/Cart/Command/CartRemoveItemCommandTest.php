<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

use Larium\Shop\Sale\Repository\InMemoryOrderRepository;

class CartRemoveItemCommandTest extends \PHPUnit_Framework_TestCase
{
    const SUCCESS_VARIANT_ID = 'PRD-01-0001';
    const SUCCESS_ORDER_NUM = '1234-ABCD';

    const FAILED_VARIANT_ID = 'PRD-01-XXXX';
    const FAILED_ORDER_NUM = '1234-XXXX';

    public function testSuccessRemoveItem()
    {
        $identifier = self::SUCCESS_VARIANT_ID;
        $orderNumber = self::SUCCESS_ORDER_NUM;

        $command = new CartRemoveItemCommand($identifier, $orderNumber);

        $orderRepository = new InMemoryOrderRepository();
        $commandHandler = new CartRemoveItemHandler($orderRepository);

        $cart = $commandHandler->handle($command);

        $this->assertEquals(0, $cart->getItems()->count());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testShouldFailWithInvalidOrderId()
    {
        $identifier = self::SUCCESS_VARIANT_ID;
        $orderNumber = self::FAILED_ORDER_NUM;

        $command = new CartRemoveItemCommand($identifier, $orderNumber);

        $orderRepository = new InMemoryOrderRepository();
        $commandHandler = new CartRemoveItemHandler($orderRepository);

        $cart = $commandHandler->handle($command);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testShouldFailWithInvalidVariantId()
    {
        $identifier = self::FAILED_VARIANT_ID;
        $orderNumber = self::SUCCESS_ORDER_NUM;

        $command = new CartRemoveItemCommand($identifier, $orderNumber);

        $orderRepository = new InMemoryOrderRepository();
        $commandHandler = new CartRemoveItemHandler($orderRepository);

        $cart = $commandHandler->handle($command);
    }
}
