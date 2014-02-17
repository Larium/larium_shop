<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

/**
 * Mulitples each given item with a fixed amount.
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class PerItem extends AbstractCalculator
{
    protected $amount;

    /**
     * {@inheritdoc}
     */
    public function compute($object = null)
    {
        $item_count = $object->getTotalQuantity();

        return $this->amount * $item_count;
    }
}
