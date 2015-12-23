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
 * FlatPercentItemTotal class.
 *
 * Calculates a flat percent of total items of Order
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreas@larium.net>
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
