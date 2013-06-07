<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Store;

require_once 'init.php';

class ProductTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testProduct()
    {
        $product = $this->getProduct('product_1');

        print_r($product->getDefaultVariant());

    }


    private function getProduct($id)
    {
        //$product = $this->loader->instanceFor('Larium\\Store\\Product', $id);
        $data = $this->loader->getData();
        
        $mapping = include __DIR__ . '/../../data_mapping.conf.php';
        $hydrator = new \Hydrator('Larium\\Store\\Product', $mapping);
        return $hydrator->hydrate($data['product_1']);
    }
}


