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

use Money\Money;

/**
 * FlatRate
 *
 * @uses AbstractCalculator
 * @author  Andreas Kollaros <andreas@larium.net>
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
        return Money::EUR($this->amount);
    }
}
