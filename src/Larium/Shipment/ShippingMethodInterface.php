<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shipment;

use Larium\Sale\OrderInterface;

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
     * @return Larium\Calculator\CalculatorInterface
     */
    public function getCalculator();
}
