<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

/**
 * FlatPercentItemTotal
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class FlatPercentItemTotal extends AbstractCalculator
{
    protected $flat_percent;

    /**
     * Cacluclates a flat percent of total items of Order
     *
     * @param Larium\Shop\Sale\Order  $object
     * @access public
     * @return float
     */
    public function compute($object = null)
    {
        $item_total = $object->getItemsTotal();
        $value = $item_total * ($this->flat_percent / 100);

        $value = (round($value * 100) / 100);

        return $value;
    }
}
