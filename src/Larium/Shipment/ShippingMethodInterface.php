<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shipment;

interface ShippingMethodInterface
{
    /**
     * Gets a the label value.
     * Label is a short description about ShippingMethod.
     *
     * @access public
     * @return string
     */
    public function getLabel();

    /**
     * Calculate the total amount to charge based on Order.
     *
     * @access public
     * @return number
     */
    public function calculateCost(OrderInterface $order);

    public function getCalculator();
}
