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
namespace Larium\Shop\Sale;

/**
 * OrderItemInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface OrderItemInterface
{
    /**
     * Sets the price of a unit.
     *
     * Total price of item will be calculated from this price and the
     * quantity.
     *
     * @param int $price
     * @return void
     */
    public function setUnitPrice($price);

    public function getUnitPrice();

    /**
     * Sets the quantity of item to be ordered.
     *
     * @return void
     */
    public function setQuantity($quantity);

    /**
     * Gets the quantity of item to be ordered.
     *
     * @return integer
     */
    public function getQuantity();

    /**
     * Sets the order object to this item.
     *
     * @param  OrderInterface $order
     * @return void
     */
    public function setOrder(OrderInterface $order);

    /**
     * Gets the order object of this item.
     *
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * Returns the total amount of item based on price per quantity and on
     * Adjustments total amount.
     *
     * @return number
     */
    public function getTotalPrice();

    public function getDescription();

    public function setDescription($description);

    /**
     * Returns the unique identifier for this object.
     *
     * @return string
     */
    public function getIdentifier();
}
