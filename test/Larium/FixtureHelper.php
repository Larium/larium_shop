<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium;

use Nelmio\Alice\Fixtures\Loader;

trait FixtureHelper
{
    private $objects;

    protected function setUp()
    {
        $this->fixturesSetUp();
    }

    protected function getValidCreditCardOptions()
    {
        return array(
            'first_name' => 'John',
            'last_name' => 'Doe',
            'month' => '2',
            'year' => date('Y') + 5,
            'number'=>'1'
        );
    }

    protected function getCartWithOneItemAndPaymentInPendingState()
    {
        $cart =$this->carts('cart_with_one_item');

        $method = $this->paymentMethods('redirect_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->setPaymentMethod($method);
        $payment->setState('pending');

        #$cart->processTo('checkout');

        return $cart;
    }

    public function __call($method, $args)
    {
        if (empty($args)) {
            return $this->objects[$method];
        }

        $key = reset($args);

        return $this->objects[$method][$key];
    }

    protected function fixturesSetUp()
    {
        $loader = new Loader();
        $this->objects['variants'] = $loader->load(__DIR__.'/../fixtures/variants.yml');
        $this->objects['products'] = $loader->load(__DIR__.'/../fixtures/products.yml');
        $this->objects['orderItems'] = $loader->load(__DIR__.'/../fixtures/order_items.yml');
        $this->objects['carts'] = $loader->load(__DIR__.'/../fixtures/carts.yml');
        $this->objects['paymentMethods'] = $loader->load(__DIR__.'/../fixtures/payment_methods.yml');
        $this->objects['shippingMethods'] = $loader->load(__DIR__.'/../fixtures/shipping_methods.yml');
        $this->objects['optionTypes'] = $loader->load(__DIR__.'/../fixtures/option_types.yml');

    }
    protected function tearDown()
    {
        $this->objects = null;
    }
}
