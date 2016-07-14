<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

use Larium\FixtureHelper;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    use FixtureHelper;

    public function testCreateProduct()
    {
        $product = new Product();

        $this->assertNotNull($product->getDefaultVariant());

        $this->assertInstanceOf('Larium\Shop\Store\Variant', $product->getDefaultVariant());

        $unit_price = 1000;
        $product->setUnitPrice($unit_price);

        $this->assertEquals($unit_price, $product->getDefaultVariant()->getUnitPrice()->getAmount());
    }
}
