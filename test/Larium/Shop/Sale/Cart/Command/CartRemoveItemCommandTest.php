<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

use Larium\Shop\Core\Event\EventProvider;
use Larium\Shop\Sale\Repository\InMemoryOrderRepository;

class CartRemoveItemCommandTest extends \PHPUnit_Framework_TestCase
{
    const SUCCESS_VARIANT_ID = 'PRD-01-0001';
    const SUCCESS_ORDER_NUM = '1234-ABCD';

    const FAILED_VARIANT_ID = 'PRD-01-XXXX';
    const FAILED_ORDER_NUM = '1234-XXXX';

    private $eventProvider;

    public function testSuccessRemoveItem()
    {
        $identifier = self::SUCCESS_VARIANT_ID;
        $orderNumber = self::SUCCESS_ORDER_NUM;

        $command = new CartRemoveItemCommand($identifier, $orderNumber);

        $commandHandler = $this->createHandler();

        $cart = $commandHandler->handle($command);

        $this->assertEquals(0, $cart->getItems()->count());

        $events = $this->getEventProvider()->releaseEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(
            'Larium\Shop\Sale\Cart\Event\ItemRemovedEvent',
            reset($events)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testShouldFailWithInvalidOrderId()
    {
        $identifier = self::SUCCESS_VARIANT_ID;
        $orderNumber = self::FAILED_ORDER_NUM;

        $command = new CartRemoveItemCommand($identifier, $orderNumber);

        $commandHandler = $this->createHandler();

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

        $commandHandler = $this->createHandler();

        $cart = $commandHandler->handle($command);
    }

    private function createHandler()
    {
        $orderRepository = new InMemoryOrderRepository();

        $handler = new CartRemoveItemHandler($orderRepository);
        $handler->setEventProvider($this->getEventProvider());

        return $handler;
    }

    private function getEventProvider()
    {
        return $this->eventProvider
            ?: $this->eventProvider = new EventProvider();
    }
}
