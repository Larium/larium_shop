<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

class OrderMock
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getItemsTotal()
    {
        return $this->options['item_total'];
    }

    public function getTotalQuantity()
    {
        return $this->options['item_count'];
    }
}

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testFlatRate()
    {
        $options = array('amount' => 4);
        $calc = new FlatRate($options);

        $opt = array('item_total' => 40.4422);
        $order = new OrderMock($opt);

        $this->assertEquals($options['amount'], $calc->compute($order));
    }

    public function testFlatPercentItemTotal()
    {
        $options = array('flat_percent' => 10);
        $calc = new FlatPercentItemTotal($options);

        $opt = array('item_total' => 40.4422);
        $order = new OrderMock($opt);

        $this->assertEquals(4.04, $calc->compute($order));
    }

    /**
     * @dataProvider flexiRateProvider
     */
    public function testFlexiRate($fi, $ai, $mi, $expects)
    {
        $options = array(
            'first_item' => $fi,
            'additional_item' => $ai,
            'max_items' =>  $mi
        );
        $calc = new FlexiRate($options);

        $opt = array('item_count' => 10);
        $order = new OrderMock($opt);

        $this->assertEquals($expects, $calc->compute($order));
    }

    public function flexiRateProvider()
    {
        return array(
            array(0, 0, 0, 0),
            array(1, 0, 0, 1),
            array(0, 1, 0, 9),
            array(5, 1, 0, 14),
            array(5, 1, 3, 26),
        );
    }

    public function testPerItem()
    {
        $options = array('amount' => 10);
        $calc = new PerItem($options);

        $opt = array('item_count' => 8);
        $order = new OrderMock($opt);

        $this->assertEquals(80, $calc->compute($order));
    }
     /**
     * @dataProvider priceSackProvider
     */
    public function testPriceSack($total, $expect)
    {
        $options = array(
            'minimal_amount' => 5,
            'normal_amount' => 10,
            'discount_amount' => 1,
        );
        $calc = new PriceSack($options);

        $opt = array('item_total' => $total);
        $order = new OrderMock($opt);

        $this->assertEquals($expect, $calc->compute($order));
    }

    public function priceSackProvider()
    {
        return array(
            array(2, 10),
            array(6, 1),
        );
    }
}
