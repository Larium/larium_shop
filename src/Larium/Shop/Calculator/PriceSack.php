<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

/**
 * PriceSack
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class PriceSack extends AbstractCalculator
{

    /**
     * The minimal amount of money needed to apply the discount amount.
     *
     * @var float|integer
     * @access protected
     */
    protected $minimal_amount = 0;

    /**
     * The amount to apply in case minimal amount is not met.
     *
     * @var float|integer
     * @access protected
     */
    protected $normal_amount = 0;

    /**
     * The amount to apply in case minimal amount is met.
     *
     * @var float|integer
     * @access protected
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
