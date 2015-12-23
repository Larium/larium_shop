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
namespace Larium\Shop\Calculator;

/**
 * PriceSack
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class PriceSack extends AbstractCalculator
{

    /**
     * The minimal amount of money needed to apply the discount amount.
     *
     * @var float|integer
     */
    protected $minimal_amount = 0;

    /**
     * The amount to apply in case minimal amount is not met.
     *
     * @var float|integer
     */
    protected $normal_amount = 0;

    /**
     * The amount to apply in case minimal amount is met.
     *
     * @var float|integer
     */
    protected $discount_amount = 0;

    public function compute($object = null)
    {
        $item_amount = $object->getItemsTotal();

        if ($item_amount < $this->minimal_amount) {
            return $this->normal_amount;
        } else {
            return $this->discount_amount;
        }
    }
}
