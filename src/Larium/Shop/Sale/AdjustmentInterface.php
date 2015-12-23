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

use Money\Money;

/**
 * AdjustmentInterface describes the behavior of an subject that when applied
 * to an Adjustable object will affect to its total amount.
 * An Adjustment can have a negative amount for credit or a positive amount for
 * charge.
 *
 * @author  Andreas Kollaros <php@andreaskollaros.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface AdjustmentInterface
{
    /**
     * Sets the amount to adjust the adjustable object.
     *
     * @param  number $amount The amount to charge or credit.
     * @return void
     */
    public function setAmount(Money $amount);

    /**
     * Gets the amount of the adjust that applied to adjustable object.
     *
     * @return number
     */
    public function getAmount();

    /**
     * Checks if adjustment will charge the adjustable object and as result
     * will increase its amount.
     *
     * @return boolean
     */
    public function isCharge();

    /**
     * Checks if adjustment will credit the adjustable object and as result
     * will decrease its amount.
     *
     * @return boolean
     */
    public function isCredit();

    /**
     * Sets the adjustable object
     *
     * @param  AdjustableInterface $adjustable
     * @return void
     */
    public function setAdjustable(AdjustableInterface $adjustable);

    /**
     * Gets the adjustable object.
     *
     * @return AdjustableInterface
     */
    public function getAdjustable();

    /**
     * Detach the adjustable from adjustment.
     *
     * @return void
     */
    public function detachAdjustable();

    /**
     * Gets label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Sets label.
     *
     * @param string $label the value to set.
     * @return void
     */
    public function setLabel($label);
}
