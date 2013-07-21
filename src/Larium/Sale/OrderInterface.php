<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\Shipment\ShippingInterface;

/**
 * Describes the interface of an Order object.
 *
 * The total amount of an Order can be adjusted from various adjustments like
 * Shipping or Billing methods, Discounts etc.
 *
 * @uses    AdjustableInterface
 * @package Larium\Sale
 * @author  Andreas Kollaros <php@andreaskollaros.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface OrderInterface extends AdjustableInterface
{
    /**
     * Return the current state of the Order.
     *
     * @access public
     * @return string|mixed
     */
    public function getState();

    /**
     * Sets the state of the Order.
     *
     * @param mixed $state
     *
     * @access public
     * @return void
     */
    public function setState($state);

    /**
     * Adds an item to OrderItem collection.
     *
     * @param  OrderItemInterface $item
     * @access public
     * @return void
     */
    public function addItem(OrderItemInterface $item);

    /**
     * Removes an item form OrderItem collection.
     *
     * @param  OrderItemInterface $item
     * @access public
     * @return void
     */
    public function removeItem(OrderItemInterface $item);

    /**
     * Checks if the collection of order items contains the specific item with
     * the same identifier.
     *
     * Returns the item found in collection or false.
     *
     * @param  OrderItemInterface $item
     * @access public
     * @return false|OrderItemInterface
     */
    public function containsItem(OrderItemInterface $item);

    /**
     * Returns a collection of items in order that are chargable
     * products
     *
     * @access public
     * @return array|Traversable
     */
    public function getItems();

    /**
     * Calculates the total amount of items in order that are chargable
     * products.
     *
     * @access public
     * @return void
     */
    public function calculateItemsTotal();

    /**
     * Gets the total amount of OrderItem collection.
     *
     * @access public
     * @return number
     */
    public function getItemsTotal();

    /**
     * Calculates the total amount of Order including Adjustments.
     *
     * @access public
     * @return void
     */
    public function calculateTotalAmount();

    /**
     * Returns the total amount of the Order.
     *
     * @access public
     * @return number
     */
    public function getTotalAmount();

    /**
     * Gets the balance amount of this order.
     * Can be a positive or negative number.
     *
     * @access public
     * @return number
     */
    public function getBalance();

    public function setShippingMethod(ShippingInterface $shipping_method);

    public function getShippingMethod();
}
