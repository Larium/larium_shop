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

use Larium\Shop\Sale\OrderInterface;
use Larium\Shop\Sale\OrderItemInterface;

/**
 * ShipmentInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface ShipmentInterface
{
    /**
     * Gets the amount to charge the Order for this Shipment.
     *
     * @access public
     * @return void
     */
    public function getCost();

    /**
     * Sets the cost for this Shipment.
     *
     * @access public
     * @return void
     */
    public function setCost($cost);

    /**
     * getOrder
     *
     * @access public
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * setOrder
     *
     * @param OrderInterface $order
     * @access public
     * @return void
     */
    public function setOrder(OrderInterface $order);

    /**
     * getAddress
     *
     * @access public
     * @return AddressInterface
     */
    public function getAddress();

    /**
     * setAddress
     *
     * @param AddressInterface $address
     * @access public
     * @return void
     */
    public function setAddress(AddressInterface $address);

    /**
     * getShippingMethod
     *
     * @access public
     * @return ShippingMethodInterface
     */
    public function getShippingMethod();

    /**
     * setShippingMethod
     *
     * @param ShippingMethodInterface $shipping_method
     * @access public
     * @return void
     */
    public function setShippingMethod(ShippingMethodInterface $shipping_method);

    /**
     * Gets order items that woudl be send with this shipment.
     *
     * @access public
     * @return Larium\Shop\Common\Collection
     */
    public function getOrderItems();

    /**
     * addOrderItem
     *
     * @param OrderItemInterface $order_item
     * @access public
     * @return void
     */
    public function addOrderItem(OrderItemInterface $order_item);

    /**
     * removeOrderItem
     *
     * @param OrderItemInterface $order_item
     * @access public
     * @return void
     */
    public function removeOrderItem(OrderItemInterface $order_item);

    /**
     * containsOrderItem
     *
     * @param OrderItemInterface $order_item
     * @access public
     * @return false|OrderItemInterface
     */
    public function containsOrderItem(OrderItemInterface $order_item);

    /**
     * Gets the unique identifier for this shipment.
     *
     * @access public
     * @return string
     */
    public function getIdentifier();

    /**
     * Detach the Order object from Shipment.
     *
     * @access public
     * @return void
     */
    public function detachOrder();
}
