<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

/**
 * AbstractCalculator class
 *
 * @uses CalculatorInterface
 * @abstract
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
abstract class AbstractCalculator implements CalculatorInterface
{
    public function __construct(array $options = array())
    {
        foreach ($options as $property=>$value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
