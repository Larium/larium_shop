<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
namespace Larium\Sale;

class Cart
{
    protected $order;

    public function addProduct(OrderableIterface $product)
    {
        $item = new OrderItem();
        $item->setType(OrderItemInterface::TYPE_PRODUCT);
        $item->setTitle($product->getTitle());
        $item->setUnitPrice($product->getUnitPrice());

        $this->getOrder()->addItem($item);
    }

    public function getOrder()
    {
        if (null === $this->order) {
            $this->order = new Order();
        }
        return $this->order;
    }
}
