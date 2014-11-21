<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

/**
 * FlatPercentItemTotal class.
 *
 * Calculates a flat percent of total items of Order
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class FlatPercentItemTotal extends AbstractCalculator
{
    protected $flat_percent;

    /**
     * {@inheritdoc}
     */
    public function compute($object = null)
    {
        $item_total = $object->getItemsTotal();
        $value = $item_total->multiply($this->flat_percent / 100);

        $value = $value->multiply(100)->divide(100);

        return $value;
    }
}
