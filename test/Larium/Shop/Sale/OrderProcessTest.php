<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

use Larium\FixtureHelper;

class OrderProcessTest extends \PHPUnit_Framework_TestCase
{
    use FixtureHelper;

    public function testCartAddingItems()
    {
        // Given i initialize a Cart object.
        $cart = new Cart();

        // Given i fetch a product
        $product1 = $this->products('product_1');

        // Given i get the default variant from product
        $variant1 = $product1->getDefaultVariant();

        // When i add the variant to cart
        $item1 = $cart->addItem($variant1);

        // Then cart should have 1 item.
        $this->assertEquals(1, $cart->getItemsCount());

        // Given i fetch another product
        $product2 = $this->products('product_2');

        // Given i get the default variant from product
        $variant2 = $product2->getDefaultVariant();

        // When i add the variant to cart
        $item2 = $cart->addItem($variant2);

        // Then cart should have 2 items
        $this->assertEquals(2, $cart->getItemsCount());

        // And cart should have 2 quantities of items
        $this->assertEquals(2, $cart->getTotalQuantity());

        // And the order should have 21 total amount
        $this->assertEquals(2100, $cart->getOrder()->getTotalAmount());
    }

    public function testCartAddSameVariant()
    {
        // Given i initialize a Cart object.
        $cart = new Cart();

        // Given i fetch a product
        $product = $this->products('product_1');

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
        $product = $this->products('product_1');

        // Given i get the default variant from product
        $variant = $product->getDefaultVariant();

        // When i add the variant to cart
        $item = $cart->addItem($variant);

        // Then order must contains that item
        $this->assertTrue(false !== $order->containsItem($item));

        // Given i fetch an item from an order
        $item = $this->orderItems('order_item_1');

        // And i assign to this item the order.
        $item->setOrder($order);

        // Then order must contains that item.
        $this->assertTrue(false !== $order->containsItem($item));
    }


    public function testOrderPaymentWithCreditCard()
    {
        // Given i fetch a cart with one item.
        $cart = $this->carts('cart_with_one_item');

        // Given i process cart to checkout state.
        $cart->processTo('checkout');

        // Given i fetch creditcard payment method.
        $method = $this->paymentMethods('creditcard_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());

        // Given i add payment method to cart.
        $payment = $cart->setPaymentMethod($method);

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
        $cart = $this->carts('cart_with_one_item');

        // Given i store order total amount.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i add cash on delivery payment method to cart.
        $method = $this->paymentMethods('cash_on_delivery_payment_method');
        $payment = $cart->setPaymentMethod($method);

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
        $cart = $this->carts('cart_with_one_item');

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i add redirect payment method to cart.
        $method = $this->paymentMethods('redirect_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->setPaymentMethod($method);

        // Given i process cart to pay state
        $cart->processTo('pay');

        // And payment should be in 'pending' state
        $this->assertEquals('pending', $payment->getState());

        // Given i fetch a cart with payment in_process state
        $cart = $this->getCartWithOneItemAndPaymentInPendingState();

        // When i process cart to pay state
        $response = $cart->processTo('pay');

        $payment = $cart->getOrder()->getPayment();

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
        $cart = $this->carts('cart_with_one_item');

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->paymentMethods('cash_on_delivery_payment_method');
        $payment = $cart->setPaymentMethod($payment_method);

        // Given i add courier shipping method to cart.
        $shipping_method = $this->shippingMethods('courier_shipping_method');

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
        $cart = $this->carts('cart_with_one_item');

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i store order total amount before adding any adjustment.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->paymentMethods('cash_on_delivery_payment_method');
        $payment = $cart->setPaymentMethod($payment_method);

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
        $cart = $this->carts('cart_with_one_item');

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i set shipping method to cart.
        $shipping_method = $this->shippingMethods('courier_shipping_method');
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
        $cart = $this->carts('cart_with_one_item');

        // Given i process cart to checkout state
        $cart->processTo('checkout');

        // Given i store order total amount before adding any adjustment.
        $total_amount = $cart->getOrder()->getTotalAmount();

        // Given i add cash on delivery payment method to cart.
        $payment_method = $this->paymentMethods('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());

        // Given i create a partial payment for the order.
        $payment = $cart->setPaymentMethod($payment_method, $total_amount - 100);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be still to checkout.
        $this->assertEquals(Order::PARTIAL_PAID, $cart->getOrder()->getState());

        $this->assertEquals(100, $cart->getOrder()->getBalance());

        // Given i add a new payment with the rest of amount
        $payment_method = $this->paymentMethods('creditcard_payment_method');
        $payment_method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->setPaymentMethod($payment_method);

        // When i process cart to pay state.
        $response = $cart->processTo('pay');

        // Then order state should be paid.
        $this->assertEquals(Order::PAID, $cart->getOrder()->getState());
    }

    /**
     * @expectedException DomainException
     */
    public function testOrderShouldNotBeEmpty()
    {
        // Given i initialize a Cart object.
        $cart = new Cart();

        $cart->processTo(Order::CHECKOUT);
    }
}
