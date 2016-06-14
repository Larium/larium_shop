<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Sale;

use Larium\Shop\Payment\PaymentMethodInterface;
use Larium\Shop\Shipment\ShippingMethodInterface;
use Money\Money;

interface CartInterface
{
    /**
     * Add an Orderable object to the Order.
     *
     * @param  OrderableInterface $orderable
     * @param  int                $quantity
     * @return OrderItem
     */
    public function addItem(OrderableInterface $orderable, $quantity = 1);

    /**
     * Removes an Orderitem from Order
     *
     * @param  OrderItem $item
     * @return void
     */
    public function removeItem(OrderItem $item);

    /**
     * Creates and adds a Payment to order based on PaymentMethod.
     *
     * Returns the Payment instance.
     *
     * @param PaymentMethodInterface $method
     * @return Larium\Shop\Payment\PaymentInterface
     */
    public function setPaymentMethod(PaymentMethodInterface $method, Money $amount = null);

    /**
     * Customer can choose a shipping method. Cart class will create a Shipment
     * for all OrderItems.
     *
     * Order can have multiple shipments that can be set up in a different
     * context.
     *
     * @param ShippingMethodInterface $shipping_method
     * @return Shipment
     */
    public function setShippingMethod(ShippingMethodInterface $shipping_method);
}
