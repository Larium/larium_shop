<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

use Larium\Shop\Payment\Payment;
use Larium\Shop\Payment\PaymentMethod;
use Larium\Shop\Payment\CreditCard;
use Finite\Event\TransitionEvent;
use Larium\Shop\Payment\Provider\RedirectResponse;

class OrderTest extends \PHPUnit_Framework_TestCase
{

    protected $loader;

    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testCartAddingItems()
    {
        // Given i initialize a Cart object.
        $cart = new Cart();

        // Given i fetch a product
        $product1 = $this->getProduct('product_1');

        // Given i get the default variant from product
        $variant1 = $product1->getDefaultVariant();

        // When i add the variant to cart
        $item1 = $cart->addItem($variant1);

        // Then cart should have 1 item.
        $this->assertEquals(1, $cart->getItemsCount());

        // Given i fetch another product
        $product2 = $this->getProduct('product_2');

        // Given i get the default variant from product
        $variant2 = $product2->getDefaultVariant();

        // When i add the variant to cart
        $item2 = $cart->addItem($variant2);

        // Then cart should have 2 items
        $this->assertEquals(2, $cart->getItemsCount());

        // And cart should have 2 quantities of items
        $this->assertEquals(2, $cart->getTotalQuantity());

        // And the order should have 21 total amount
        $this->assertEquals(21, $cart->getOrder()->getTotalAmount());

    }

    public function testCartAddSameVariant()
    {
        // Given i initialize a Cart object.
        $cart = new Cart();

        // Given i fetch a product
        $product = $this->getProduct('product_1');

        // Given i get the default variant from product
        $variant = $product->getDefaultVariant();

        // When i add the variant twice to cart
        $cart->addItem($variant);
        $cart->addItem($variant);

        // Then cart should have 1 item
        $this->assertEquals(1, $cart->getItemsCount());

        // And the quantity should be equals to 2
        $this->assertEquals(2, $cart->getOrder()->getTotalQuantity());
    }


    public function testOrderContainsItem()
    {
        // Given i initialize a Cart object.
        $cart = new Cart();

        // Given i get the order from cart.
        $order = $cart->getOrder();

        // Given i fetch a product
        $product = $this->getProduct('product_1');

        // Given i get the default variant from product
        $variant = $product->getDefaultVariant();

        // When i add the variant to cart
        $item = $cart->addItem($variant);

        // Then order must contains that item
        $this->assertTrue(false !== $order->containsItem($item));

        // Given i fetch an item from an order
        $item = $this->getOrderItem('order_item_1');

        // And i assign to this item the order.
        $item->setOrder($order);

        // Then order must contains that item.
        $this->assertTrue(false !== $order->containsItem($item));
    }


    public function testOrderPaymentWithCreditCard()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state.
        $cart->processTo('checkout');

        // Given i fetch creditcard payment method.
        $method = $this->getPaymentMethod('creditcard_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());

        // Given i add payment method to cart.
        $payment = $cart->addPaymentMethod($method);

        // When i process cart to pay state.
        $cart->processTo('pay');

        // Then order should have 'paid' state
        $this->assertEquals('paid', $cart->getOrder()->getState());

        // And payment should have 'paid' state
        $this->assertEquals('paid', $payment->getState());

        // And credit card number should be the one i fetched.
        $this->assertEquals('1', $method->getPaymentSource()->getNumber());

        // And order should have zero balance.
        $this->assertEquals(0, $cart->getOrder()->getBalance());
    }

    public function testOrderPaymentWithCashOnDelivery()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i store order total amount.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i add cash on delivery payment method to cart.
        $method = $this->getPaymentMethod('cash_on_delivery_payment_method');
        $payment = $cart->addPaymentMethod($method);

        // When i process cart to pay state.
        $cart->processTo('pay');

        // Then total amount of order should be increased.
        $this->assertTrue($cart->getOrder()->getTotalAmount() > $total_amount);

        // And order state should be 'paid'
        $this->assertEquals('paid', $cart->getOrder()->getState());

        // And payment state should be 'paid'
        $this->assertEquals('paid', $payment->getState());

        // And order should have zero balance.
        $this->assertEquals(0, $cart->getOrder()->getBalance());
    }

    public function testRedirectPayment()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i add redirect payment method to cart.
        $method = $this->getPaymentMethod('redirect_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->addPaymentMethod($method);

        // Given i process cart to pay state
        $cart->processTo('pay');

        $response = $cart->getOrder()->getCurrentPayment()->getResponse();

        // Then response should be instance of 'Larium\Shop\Payment\Provider\RedirectResponse'
        $this->assertInstanceOf('Larium\Shop\Payment\Provider\RedirectResponse', $response);

        // And payment should be in 'in_progress' state
        $this->assertEquals('in_progress', $payment->getState());

        // Given i fetch a cart with payment in_process state
        $cart = $this->getCartWithOneItemAndPaymentInProgressState();

        // When i process cart to pay state
        $response = $cart->processTo('pay');

        $payment = $cart->getOrder()->getCurrentPayment();

        // Then payment state should be 'paid'
        $this->assertTrue($payment->getState() == 'paid');

        // And payment should have a transaction.
        $this->assertTrue($payment->getTransactions()->count() > 0);

        // And transaction of payment should have an id.
        $this->assertNotNull($payment->getTransactions()->first()->getTransactionId());
    }

    public function testOrderWithShippingMethod()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->getPaymentMethod('cash_on_delivery_payment_method');
        $payment = $cart->addPaymentMethod($payment_method);

        // Given i add courier shipping method to cart.
        $shipping_method = $this->getShippingMethod('courier_shipping_method');

        // Given i set shipping method to cart.
        $cart->setShippingMethod($shipping_method);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order balance should be zero.
        $this->assertEquals(0, $cart->getOrder()->getBalance());

        // And order shipping cost should be equal to shipping method cost
        $this->assertEquals($shipping_method->calculateCost($cart->getOrder()), $cart->getOrder()->getShippingCost());
    }

    public function testRemovePaymentWillRemoveAdjustmentToo()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i store order total amount before adding any adjustment.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->getPaymentMethod('cash_on_delivery_payment_method');
        $payment = $cart->addPaymentMethod($payment_method);

        // Then order should have at least one adjustment
        $this->assertTrue($cart->getOrder()->getAdjustments()->count() != 0);

        // When i remove the payment
        $cart->getOrder()->removePayment($payment);

        // Then order should have not any adjustments
        $this->assertTrue($cart->getOrder()->getAdjustments()->count() == 0);

        // And order total amount should be the same with the one i stored.
        $this->assertEquals($total_amount, $cart->getOrder()->getTotalAmount());
    }

    public function testRemoveShipmentWillRemoveAdjustmentToo()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i set shipping method to cart.
        $shipping_method = $this->getShippingMethod('courier_shipping_method');
        $shipment = $cart->setShippingMethod($shipping_method);

        // Then order should have at least one adjustment
        $this->assertTrue($cart->getOrder()->getAdjustments()->count() != 0);

        // When i remove the payment
        $cart->getOrder()->removeShipment($shipment);

        // Then order should have not any adjustments
        $this->assertTrue($cart->getOrder()->getAdjustments()->count() == 0);
    }

    public function testRollbackPayment()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i store order total amount before adding any adjustment.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->getPaymentMethod('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());

        // Given i create a partial payment for the order.
        $payment = $cart->addPaymentMethod($payment_method, $total_amount - 1);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be still to checkout.
        $this->assertEquals(Order::PARTIAL_PAID, $cart->getOrder()->getState());

        $this->assertEquals(1, $cart->getOrder()->getBalance());

        // Given i add a new payment with the rest of amount
        $payment_method = $this->getPaymentMethod('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->addPaymentMethod($payment_method);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be paid.
        $this->assertEquals(Order::PAID, $cart->getOrder()->getState());
    }

    public function testMultiplePartialPayments()
    {
        // Given i fetch a cart with one item.
        $cart = $this->getCartWithOneItem();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i store order total amount before adding any adjustment.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->getPaymentMethod('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());

        // Given i create a partial payment for the order.
        $payment = $cart->addPaymentMethod($payment_method, $total_amount - 4);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be partial_paid.
        $this->assertEquals(Order::PARTIAL_PAID, $cart->getOrder()->getState());

        $this->assertEquals(4, $cart->getOrder()->getBalance());

        // Given i add a new payment with another amount.
        $payment_method = $this->getPaymentMethod('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->addPaymentMethod($payment_method, 2);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be partial_paid.
        $this->assertEquals(Order::PARTIAL_PAID, $cart->getOrder()->getState());

        // Given i add a new payment with the rest of amount.
        $payment_method = $this->getPaymentMethod('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->addPaymentMethod($payment_method, 2);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be partial_paid.
        $this->assertEquals(Order::PAID, $cart->getOrder()->getState());

        $this->assertEquals(3, $cart->getOrder()->getPayments()->count());
    }

    /*- ( Fixtures ) -------------------------------------------------------- */

    private function getCartWithOneItem()
    {
        $cart = new Cart();
        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();
        $item = $cart->addItem($variant);

        return $cart;
    }

    private function getCartWithOneItemAndPaymentInProgressState()
    {
        $cart = new Cart();
        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();
        $item = $cart->addItem($variant);

        $method = $this->getPaymentMethod('redirect_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = new Payment();

        $payment->setState('in_progress');
        $payment->setPaymentMethod($method);
        $cart->getOrder()->addPayment($payment);

        $cart->processTo('checkout');

        return $cart;
    }

    private function getProduct($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Store\\Product');

        return $hydrator->hydrate($data[$id], $id);
    }

    private function getOrderItem($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Sale\\OrderItem');

        return $hydrator->hydrate($data[$id], $id);
    }

    private function getPaymentMethod($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Payment\\PaymentMethod');

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

    private function getShippingMethod($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Shipment\\ShippingMethod');

        return $hydrator->hydrate($data[$id], $id);
    }
}
