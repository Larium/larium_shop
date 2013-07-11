<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\Payment\Payment;
use Larium\Payment\CreditCard;

require_once 'init.php';

class OrderTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testCartAddingItems()
    {
        $cart = new Cart();

        $product1 = $this->getProduct('product_1');
        $variant1 = $product1->getDefaultVariant();
        $item1 = $cart->addItem($variant1);

        $this->assertEquals(1, $cart->getItemsCount());

        $product2 = $this->getProduct('product_2');
        $variant2 = $product2->getDefaultVariant();
        $item2 = $cart->addItem($variant2);
        
        $this->assertEquals(2, $cart->getItemsCount());
        $this->assertEquals(2, $cart->getTotalQuantity());

        $this->assertEquals(21, $cart->getOrder()->getTotalAmount());

    }
    
    public function testCartAddSameVariant()
    {
        $cart = new Cart();

        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();

        $cart->addItem($variant);
        $cart->addItem($variant);

        $this->assertEquals(1, $cart->getItemsCount());
        $this->assertEquals(2, $cart->getOrder()->getTotalQuantity());
    }


    public function testOrderContainsItem()
    {
        $cart = new Cart();
        $order = $cart->getOrder();
        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();
        $item = $cart->addItem($variant);

        $this->assertTrue(false !== $order->containsItem($item));

        $item = $this->getOrderItem('order_item_1');

        $item->setOrder($order);

        $this->assertTrue(false !== $order->containsItem($item));
    }

    private function getProduct($id)
    {
        $data = $this->loader->getData();
        
        $hydrator = new \Hydrator('Larium\\Store\\Product');
        
        return $hydrator->hydrate($data[$id], $id);
    }

    private function getOrderItem($id)
    {
        $data = $this->loader->getData();
        
        $hydrator = new \Hydrator('Larium\\Sale\\OrderItem');
        
        return $hydrator->hydrate($data[$id], $id);
    }
}
