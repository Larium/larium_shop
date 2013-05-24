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

    public function testOrder()
    {
        $cart = new Cart();

        $p1 = $this->loader->instanceFor('Larium\\Store\\Product', 'product_1');
        $p2 = $this->loader->instanceFor('Larium\\Store\\Product', 'product_2');
        $p3 = $this->loader->instanceFor('Larium\\Store\\Product', 'product_3');
        
        $b = $this->loader->instanceFor('Larium\\Payment\\BillingMethod', 'cash');

        $item1 = $cart->addProduct($p1);
        
        $item2 = $cart->addProduct($p2);

        $this->assertEquals(18, $cart->getOrder()->getTotalAmount());

        //$cart->removeItem($item2);

        $cart->addBillingMethod($b);
        print_r($cart);
    } 
}
