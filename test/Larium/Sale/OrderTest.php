<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\Payment\Payment;
use Larium\Payment\PaymentMethod;
use Larium\Payment\CreditCard;

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


    public function testOrderPaymentWithCreditCard()
    {
        $cart = $this->getCartWithOneItem();

        $cart->processTo('checkout');

        $method = $this->getPaymentMethod('creditcard_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());

        $payment = $cart->addPaymentMethod($method);

        $cart->processTo('pay');

        $this->assertEquals('paid', $cart->getOrder()->getState());
        $this->assertEquals('paid', $payment->getState());
        $this->assertEquals('1', $method->getPaymentSource()->getNumber());
        $this->assertEquals(0, $cart->getOrder()->getBalance());
    }

    public function testOrderPaymentWithCashOnDelivery()
    {
        $cart = $this->getCartWithOneItem();

        $total_amount = $cart->getOrder()->getTotalAmount();

        $cart->processTo('checkout');

        $method = $this->getPaymentMethod('cash_on_delivery_payment_method');

        $payment = $cart->addPaymentMethod($method);

        $cart->processTo('pay');

        $this->assertTrue($cart->getOrder()->getTotalAmount() > $total_amount);
        $this->assertEquals('paid', $cart->getOrder()->getState());
        $this->assertEquals('paid', $payment->getState());
        $this->assertEquals(0, $cart->getOrder()->getBalance());

    }

    private function getCartWithOneItem()
    {
        $cart = new Cart();
        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();
        $item = $cart->addItem($variant);

        return $cart;
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

    private function getPaymentMethod($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\\Payment\\PaymentMethod');

        return $hydrator->hydrate($data[$id], $id);
    }

    private function getValidCreditCardOptions()
    {
        return array(
            'first_name' => 'John',
            'last_name' => 'Doe',
            'month' => '2',
            'year' => date('Y') + 5,
            'number'=>'1'
        );
    }
}
