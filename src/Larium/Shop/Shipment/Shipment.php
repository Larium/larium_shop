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
namespace Larium\Shop\Shipment;

use Larium\Shop\Sale\Adjustment;
use Larium\Shop\Common\Collection;
use Larium\Shop\Sale\OrderInterface;
use Larium\Shop\Sale\OrderItemInterface;

/**
 * Shipment
 *
 * @uses ShipmentInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class Shipment implements ShipmentInterface
{
    /**
     * @var Larium\Shop\Sale\Order
     */
    protected $order;

    /**
     * @var Larium\Shop\Shipment\Address
     */
    protected $address;

    /**
     * @var Larium\Shop\Shipment\ShippingMethod
     */
    protected $shipping_method;

    /**
     * @var Larium\Shop\Common\Collection
     */
    protected $order_items;

    /**
     * @var int
     */
    protected $cost = 0;

    /**
     * @var string
     */
    protected $identifier;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->order_items = new Collection();

        $this->generateIdentifier();
    }

    /**
     * {@inheritdoc }
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
        $this->cost = $this->getShippingMethod()->calculateCost($order);
        if ($this->cost) {
            $adj = new Adjustment();
            $adj->setAmount($this->cost);
            $adj->setLabel($this->getIdentifier());

            $order->addAdjustment($adj);

            $order->calculateTotalAmount();
        }
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
    public function setShippingMethod(ShippingMethodInterface $shipping_method)
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
        $this->order_items->add($order_item);
    }

    /**
     * {@inheritdoc }
     */
    public function removeOrderItem(OrderItemInterface $order_item)
    {
        if ($item = $this->containsOrderItem($order_item)) {
            return $this->order_items->remove($item);
        }

        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function containsOrderItem(OrderItemInterface $order_item)
    {

        return $this->order_items->contains($order_item, function ($item) use ($order_item) {
            return $item->getIdentifier() == $order_item->getIdentifier();
        });

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function detachOrder()
    {
        $label = $this->getIdentifier();

        foreach ($this->order->getAdjustments() as $key => $a) {
            if ($label == $a->getLabel()) {
                $this->order->getAdjustments()->remove($a);
                $this->order = null;

                return true;
                break;
            }
        }

        return false;
    }

    /**
     * Generates a unique identifier for this shipment.
     *
     * @return string
     */
    protected function generateIdentifier()
    {
        $this->identifier = uniqid();
    }
}
