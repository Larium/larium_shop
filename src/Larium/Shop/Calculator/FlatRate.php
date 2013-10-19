<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Calculator;

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
        return $this->amount;
    }
}
