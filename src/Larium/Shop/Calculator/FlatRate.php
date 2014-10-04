<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

use Money\Money;

/**
 * FlatRate
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class FlatRate extends AbstractCalculator
{
    /**
     * The fixed amount to charge.
     *
     * @var number
     * @access protected
     */
    protected $amount;

    /**
     * {@inheritdoc}
     */
    public function compute($object = null)
    {
        return Money::EUR($this->amount * 100);
    }
}
