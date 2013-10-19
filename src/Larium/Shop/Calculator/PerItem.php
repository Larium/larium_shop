<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

class PerItem extends AbstractCalculator
{
    protected $amount;

    public function compute($object = null)
    {
        $item_count = $object->getTotalQuantity();

        return $this->amount * $item_count;
    }
}
