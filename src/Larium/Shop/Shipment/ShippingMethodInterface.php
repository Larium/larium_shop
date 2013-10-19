<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

use Larium\Shop\Sale\OrderInterface;

/**
 * ShippingMethodInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface ShippingMethodInterface
{
    /**
     * Gets the label of ShippingMehod.
     *
     * Label is a short description about ShippingMethod.
     *
     * @access public
     * @return string
     */
    public function getLabel();

    /**
     * Calculate the total amount to charge.
     *
     * @access public
     * @return number
     */
    public function calculateCost(OrderInterface $order = null);

    /**
     * Gets the Calculator instance for this ShippingMethod.
     *
     * @access public
     * @return Larium\Shop\Calculator\CalculatorInterface
     */
    public function getCalculator();
}
