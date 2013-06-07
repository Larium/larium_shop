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
    } 
}
