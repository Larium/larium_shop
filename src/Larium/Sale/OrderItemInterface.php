<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

interface OrderItemInterface
{
    /**
     * Sets the price of a unit.
     *
     * Total price of item will be calculated from this price and the
     * quantity.
     *
     * @param number $price
     * @access public
     * @return void
     */
    public function setUnitPrice($price);

    public function getUnitPrice();

    /**
     * Sets the quantity of item to be ordered.
     *
     * @access public
     * @return void
     */
    public function setQuantity($quantity);

    /**
     * Gets the quantity of item to be ordered.
     *
     * @access public
     * @return integer
     */
    public function getQuantity();

    /**
     * Sets the order object to this item.
     *
     * @param  OrderInterface $order
     * @access public
     * @return void
     */
    public function setOrder(OrderInterface $order);

    /**
     * Gets the order object of this item.
     *
     * @access public
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * Returns the total amount of item based on price per quantity and on
     * Adjustments total amount.
     *
     * @access public
     * @return number
     */
    public function getTotalPrice();

    public function getDescription();

    public function setDescription($description);

    /**
     * Returns the unique identifier for thjis object.
     *
     * @access public
     * @return void
     */
    public function getIdentifier();
}
