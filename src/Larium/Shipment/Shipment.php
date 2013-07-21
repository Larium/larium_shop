<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shipment;

use Larium\Sale\OrderInterface;
use Larium\Sale\OrderItemInterface;

class Shipment
{
    protected $order;

    protected $address;

    protected $shipping_method;

    protected $order_items;

    protected $cost;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->order_items = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc }
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * {@inheritdoc }
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * {@inheritdoc }
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc }
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * {@inheritdoc }
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc }
     */
    public function setAddress(AddressInterface $address)
    {
        $this->address = $address;
    }

    /**
     * {@inheritdoc }
     */
    public function getShippingMethod()
    {
        return $this->shipping_method;
    }

    /**
     * {@inheritdoc }
     */
    public function setShippingMethod($shipping_method)
    {
        $this->shipping_method = $shipping_method;
    }

    /**
     * {@inheritdoc }
     */
    public function getOrderItems()
    {
        return $this->order_items;
    }

    /**
     * {@inheritdoc }
     */
    public function addOrderItem(OrderItemInterface $order_item)
    {
        $this->order_items->attach($order_item);
    }

    /**
     * {@inheritdoc }
     */
    public function removeOrderItem(OrderItemInterface $order_item)
    {
        if ($item = $this->containsOrderItem($order_item)) {
            $this->order_items->detach($item);
        }
    }

    /**
     * {@inheritdoc }
     */
    public function containsOrderItem(OrderItemInterface $order_item)
    {
        foreach ($this->order_items as $item) {
            if ($item->getIdentifier() == $order_item->getIdentifier()) {
                return $item;
            }
        }

        return false;
    }
}
