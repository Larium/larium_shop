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
 * CalculatorInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
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
