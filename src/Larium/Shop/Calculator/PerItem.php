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
 * Mulitples each given item with a fixed amount.
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreas@larium.net>
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
