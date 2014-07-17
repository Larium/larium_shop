<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

use Larium\Shop\Payment\PaymentMethodInterface;
use Larium\Shop\Shipment\ShippingMethodInterface;

interface CartInterface
{
    const CART       = 'cart';

    const CHECKOUT   = 'checkout';

    const PARTIAL_PAID = 'partial_paid';

    const PAID       = 'paid';

    const PROCESSING = 'processing';

    const SENT       = 'sent';

    const CANCELLED  = 'cancelled';

    const DELIVERED  = 'delivered';

    const RETURNED   = 'returned';

    /**
     * Add an Orderable object to the Order.
     *
     * @param  OrderableInterface $orderable
     * @param  int                $quantity
     * @access public
     * @return OrderItem
     */
    public function addItem(OrderableInterface $orderable, $quantity = 1);

    /**
     * Removes an Orderitem from Order
     *
     * @param  OrderItem $item
     * @access public
     * @return void
     */
    public function removeItem(OrderItem $item);

    /**
     * Creates and adds a Payment to order based on PaymentMethod.
     *
     * Returns the Payment instance.
     *
     * @param PaymentMethodInterface $method
     * @access public
     * @return Larium\Shop\Payment\PaymentInterface
     */
    public function addPaymentMethod(PaymentMethodInterface $method, $amount = null);

    /**
     * Customer can choose a shipping method. Cart class will create a Shipment
     * for all OrderItems.
     *
     * Order can have multiple shipments that can be set up in a different
     * context.
     *
     * @param ShippingMethodInterface $shipping_method
     * @access public
     * @return Shipment
     */
    public function setShippingMethod(ShippingMethodInterface $shipping_method);
}
