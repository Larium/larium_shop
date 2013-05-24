<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

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
     * Return the current status of the Order.
     * 
     * @access public
     * @return string|mixed
     */
    public function getStatus();

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
     * Checks if OrderItem collection contains the specific item. 
     * 
     * @param  OrderItemInterface $item 
     * @access public
     * @return boolean
     */
    public function containsItem(OrderItemInterface $item);

    /**
     * Returns a collection of items in order that are chargable 
     * products
     * 
     * @access public
     * @return array|mixed
     */
    public function getItems();

    /**
     * Calculates the total amount of items in order that are chargable 
     * products.
     * 
     * @access public
     * @return void
     */
    public function calculateProductsTotal();
    
    /**
     * Gets the total amount of OrderItem collection.
     * 
     * @access public
     * @return number
     */
    public function getProductsTotal();

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
}
