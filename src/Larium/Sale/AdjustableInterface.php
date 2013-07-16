<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

/**
 * AdjustableInterface contains a collection of Adjustments and calculates a
 * total amount based on the behaviour of each Adjustment.
 *
 * @package Larium\Sale
 * @author  Andreas Kollaros <php@andreaskollaros.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface AdjustableInterface
{

    /**
     * Adds an Adjustment to Adjustable
     *
     * @param  AdjustmentInterface $adjustment
     * @access public
     * @return void
     */
    public function addAdjustment(AdjustmentInterface $adjustment);

    /**
     * Removes an Adjustment
     *
     * @param  AdjustmentInterface $adjustment
     * @access public
     * @return void
     */
    public function removeAdjustment(AdjustmentInterface $adjustment);

    /**
     * Checks if given Adjustment exists in Adjustable.
     *
     * @param  AdjustmentInterface $adjustment
     * @access public
     * @return boolean
     */
    public function containsAdjustment(AdjustmentInterface $adjustment);

    /**
     * Gets all Adjustments for Adjustable object.
     *
     * @access public
     * @return array|Traversable
     */
    public function getAdjustments();

    /**
     * Calculates the total amount of Adjustments.
     *
     * @access public
     * @return void
     */
    public function calculateAdjustmentsTotal();

    /**
     * Returns the total amount of all Adjustments
     *
     * @access public
     * @return number
     */
    public function getAdjustmentsTotal();
}
