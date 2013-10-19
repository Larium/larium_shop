<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

/**
 * CalculatorInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface CalculatorInterface
{
    /**
     * Computes and returns a final value based on given object.
     *
     * @param mixed $object
     * @access public
     * @return number
     */
    public function compute($object = null);
}
