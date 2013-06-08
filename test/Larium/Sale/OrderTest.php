<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

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

    private function getProduct($id)
    {
        $data = $this->loader->getData();
        
        $mapping = include __DIR__ . '/../../data_mapping.conf.php';
        $hydrator = new \Hydrator('Larium\\Store\\Product', $mapping);
        return $hydrator->hydrate($data[$id]);
    }
}
