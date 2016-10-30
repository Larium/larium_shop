<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

use Larium\Shop\Core\Event\EventProvider;
use Larium\Shop\Sale\Repository\InMemoryOrderRepository;
use Larium\Shop\Catalog\Repository\InMemoryVariantRepository;

class CartAddItemCommandTest extends \PHPUnit_Framework_TestCase
{
    const SUCCESS_VARIANT_ID = 'PRD-01-0001';

    const FAILED_VARIANT_ID = 'PRD-01-XXXX';

    private $eventProvider;

    public function testSuccessAddItem()
    {
        $sku = self::SUCCESS_VARIANT_ID;
        $quantity = 1;
        $command = new CartAddItemCommand($sku, $quantity);

        $commandHandler = $this->createHandler();

        $cart = $commandHandler->handle($command);

        $this->assertInstanceOf('Larium\Shop\Sale\Cart', $cart);
        $this->assertEquals($quantity, $cart->getTotalQuantity());

        $events = $this->getEventProvider()->releaseEvents();

        $this->assertNotEmpty($events);
        $this->assertInstanceOf(
            'Larium\Shop\Sale\Cart\Event\ItemAddedEvent',
            reset($events)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFailedAddItem()
    {
        $sku = self::FAILED_VARIANT_ID;
        $quantity = 1;
        $command = new CartAddItemCommand($sku, $quantity);

        $commandHandler = $this->createHandler();

        $cart = $commandHandler->handle($command);
    }

    private function createHandler()
    {
        $variantRepository = new InMemoryVariantRepository();
        $orderRepository = new InMemoryOrderRepository();

        $handler = new CartAddItemHandler(
            $variantRepository,
            $orderRepository
        );

        $handler->setEventProvider($this->getEventProvider());

        return $handler;
    }

    private function getEventProvider()
    {
        return $this->eventProvider
            ?: $this->eventProvider = new EventProvider();
    }
}
