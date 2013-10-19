<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

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
