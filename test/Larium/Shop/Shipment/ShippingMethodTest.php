<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

use Larium\FixtureHelper;

class ShippingMethodTest extends \PHPUnit_Framework_TestCase
{
    use FixtureHelper;

    public function testInstance()
    {
        $shipping_method = $this->shippingMethods('courier_shipping_method');

        $this->assertInstanceOf(
            'Larium\Shop\\Calculator\\CalculatorInterface',
            $shipping_method->getCalculator()
        );

        $this->assertNotEquals(
            null,
            $shipping_method->getLabel()
        );
    }

    public function testShippingMethodCalculator()
    {
        $shipping_method = $this->shippingMethods('courier_shipping_method');

        $this->assertEquals(
            500,
            $shipping_method->calculateCost()->getAmount()
        );
    }
}
