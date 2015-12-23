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
 * AbstractCalculator class
 *
 * @uses CalculatorInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
abstract class AbstractCalculator implements CalculatorInterface
{
    public function __construct(array $options = array())
    {
        foreach ($options as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
