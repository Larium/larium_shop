<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

class ProductTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testCreateProduct()
    {
        $product = new Product();

        $this->assertFalse(is_null($product->getDefaultVariant()));

        $this->assertInstanceOf('Larium\Shop\Store\Variant', $product->getDefaultVariant());

        $unit_price = 10;
        $product->setUnitPrice($unit_price);

        $this->assertEquals($unit_price, $product->getDefaultVariant()->getUnitPrice());
    }



    /*- ( Fixtures ) -------------------------------------------------------- */

    private function getProduct($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Store\\Product');

        return $hydrator->hydrate($data[$id], $id);
    }
}
