<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Event;

use Larium\Shop\Sale\Cart;
use Larium\Shop\Sale\OrderItem;
use Larium\Shop\Core\Event\DomainEvent;

class ItemRemovedEvent extends ItemEvent
{
    const NAME = 'cart.item.removed';
}
