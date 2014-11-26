<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

use Money\Money;

/**
 * Allows compute fees based on rules in order items.
 *
 * Available options are:
 * - first_item:      The amount to charge for the first item of an order.
 * - additional_item: The amount to charge for additional items of an order.
 * - max_items:       Limits the number of first_item_amount + additional_item_amount.
 *                    Charge will cycle based in max_items. Example if order has 10 items,
 *                    max_items is 3, first_item_amount is 5 and additional_item_amount is 1 then
 *                    for the first group of 3 items will charge 5 for the first and 1
 *                    for the rest two. Fourth item will be charge 5 fifth and sixth
 *                    1 and so on.
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class FlexiRate extends AbstractCalculator
{
    protected $first_item_amount = 0;

    protected $additional_item_amount = 0;

    protected $max_items = 0;

    /**
     * {@inheritdoc}
     */
    public function compute($object = null)
    {
        $sum = 0;
        $max = $this->max_items;
        $items_count = $object->getTotalQuantity();
        for ($i=0; $i < $items_count; $i++) {
            if ((0 == $max && 0 == $i) || ($max > 0) && ($i % $max == 0)) {
                $sum += $this->first_item_amount;
            } else {
                $sum += $this->additional_item_amount;
            }
        }

        return Money::EUR($sum);
    }
}
