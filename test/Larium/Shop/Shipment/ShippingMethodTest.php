<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

class ShippingMethodTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    protected $data;

    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testInstance()
    {
        $shipping_method = $this->getShippingMethod('courier_shipping_method');

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
        $shipping_method = $this->getShippingMethod('courier_shipping_method');

        $this->assertEquals(
            $this->data['courier_shipping_method']['calculator_options']['amount'],
            $shipping_method->calculateCost()->getAmount()
        );
    }

    /*- ( Fixtures ) -------------------------------------------------------- */

    private function getShippingMethod($id)
    {
        $this->data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Shipment\\ShippingMethod');

        return $hydrator->hydrate($this->data[$id], $id);
    }
}
