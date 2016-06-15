<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium;

use Larium\Shop\Sale\Cart;
use Larium\Shop\Payment\Payment;

trait Helper
{
    protected function getCartWithOneItem()
    {
        $cart = new Cart();
        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();
        $item = $cart->addItem($variant);

        return $cart;
    }

    protected function getCartWithOneItemAndPaymentInPendingState()
    {
        $cart = new Cart();
        $product = $this->getProduct('product_1');
        $variant = $product->getDefaultVariant();
        $item = $cart->addItem($variant);

        $method = $this->getPaymentMethod('redirect_payment_method');
        $method->setSourceOptions($this->getValidCreditCardOptions());
        $payment = $cart->setPaymentMethod($method);
        $payment->setState('pending');

        $cart->processTo('checkout');

        return $cart;
    }

    protected function getProduct($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Store\\Product');

        return $hydrator->hydrate($data[$id], $id);
    }

    protected function getOrderItem($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Sale\\OrderItem');

        return $hydrator->hydrate($data[$id], $id);
    }

    protected function getPaymentMethod($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Payment\\PaymentMethod');

        return $hydrator->hydrate($data[$id], $id);
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

    protected function getShippingMethod($id)
    {
        $data = $this->loader->getData();

        $hydrator = new \Hydrator('Larium\Shop\\Shipment\\ShippingMethod');

        return $hydrator->hydrate($data[$id], $id);
    }
}
