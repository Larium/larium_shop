<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

class ShippingItem extends OrderItem
{
    protected $type = OrderItemInterface::TYPE_SHIPPING;

    protected $sku = 'Shipping Method #1';
}
