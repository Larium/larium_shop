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
 * AdjustableInterface contains a collection of Adjustments and calculates a
 * total amount based on the behaviour of each Adjustment.
 *
 * @author  Andreas Kollaros <php@andreaskollaros.com>
 */
interface AdjustableInterface
{

    /**
     * Adds an Adjustment to Adjustable
     *
     * @param  AdjustmentInterface $adjustment
     * @return void
     */
    public function addAdjustment(AdjustmentInterface $adjustment);

    /**
     * Removes an Adjustment
     *
     * @param  AdjustmentInterface $adjustment
     * @return void
     */
    public function removeAdjustment(AdjustmentInterface $adjustment);

    /**
     * Checks if given Adjustment exists in Adjustable.
     *
     * @param  AdjustmentInterface $adjustment
     * @return boolean
     */
    public function containsAdjustment(AdjustmentInterface $adjustment);

    /**
     * Gets all Adjustments for Adjustable object.
     *
     * @return array|Traversable
     */
    public function getAdjustments();

    /**
     * Calculates the total amount of Adjustments.
     *
     * @return void
     */
    public function calculateAdjustmentsTotal();

    /**
     * Returns the total amount of all Adjustments
     *
     * @return number
     */
    public function getAdjustmentsTotal();
}
