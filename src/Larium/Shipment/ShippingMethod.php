<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shipment;

use Larium\Sale\OrderInterface;

class ShippingMethod implements ShippingMethodInterface
{
    protected $label;

    protected $calculator_class;

    protected $calculator;

    /**
     * Gets label.
     *
     * @access public
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets label.
     *
     * @param mixed $label the value to set.
     * @access public
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function calculateCost(OrderInterface $order)
    {
        return $this->getCalculator()->compute($order);
    }

    public function getCalculator()
    {
        if (null === $this->calculator) {

            $class = $this->calculator_class;

            $this->calculator = new $class();
        }

        return $this->calculator;
    }

    /**
     * Gets calculator_class.
     *
     * @access public
     * @return mixed
     */
    public function getCalculatorClass()
    {
        return $this->calculator_class;
    }

    /**
     * Sets calculator_class.
     *
     * @param mixed $calculator_class the value to set.
     * @access public
     * @return void
     */
    public function setCalculatorClass($calculator_class)
    {
        $this->calculator_class = $calculator_class;
    }
}
