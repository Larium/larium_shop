<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Larium\Shop\Sale\OrderInterface;

/**
 * ShippingMethodInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface ShippingMethodInterface
{
    /**
     * Gets the label of ShippingMehod.
     *
     * Label is a short description about ShippingMethod.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Calculate the total amount to charge.
     *
     * @return number
     */
    public function calculateCost(OrderInterface $order = null);

    /**
     * Gets the Calculator instance for this ShippingMethod.
     *
     * @return Larium\Shop\Calculator\CalculatorInterface
     */
    public function getCalculator();
}
