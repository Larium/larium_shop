<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

use Larium\Shop\Sale\OrderInterface;

/**
 * ShippingMethod
 *
 * @uses ShippingMethodInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class ShippingMethod implements ShippingMethodInterface
{
    /**
     * label
     *
     * @var string
     * @access protected
     */
    protected $label;

    /**
     * calculator_class
     *
     * @var string
     * @access protected
     */
    protected $calculator_class;

    /**
     * calculator
     *
     * @var Larium\Shop\Calculator\AbstractCalculator
     * @access protected
     */
    protected $calculator;

    /**
     * calculator_options
     *
     * @var array
     * @access protected
     */
    protected $calculator_options;

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

    /**
     * Calculates shipping costs based on associated Calculator.
     *
     * OrderInterface argument provides the necessary info to calculator to
     * compute costs for ShippingMethod.
     *
     * @param OrderInterface $order
     * @access public
     * @return number
     */
    public function calculateCost(OrderInterface $order = null)
    {
        return $this->getCalculator()->compute($order);
    }

    /**
     * Gets a Calclulator instance to compute shipping costs.
     *
     * @access public
     * @return Larium\Shop\Calculator\CalculatorInterface
     */
    public function getCalculator()
    {
        if (null === $this->calculator) {

            $class = $this->calculator_class;

            $this->calculator = new $class($this->getCalculatorOptions());
        }

        return $this->calculator;
    }

    /**
     * Gets calculator_class.
     *
     * @access public
     * @return string
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

    /**
     * Gets an options array for instantiate a calculator class.
     *
     * @access public
     * @return array
     */
    public function getCalculatorOptions()
    {
        return $this->calculator_options;
    }

    /**
     * Sets an options array for instantiate a calculator class.
     *
     * @param array $calculator_options.
     *
     * @access public
     * @return void
     */
    public function setCalculatorOptions(array $calculator_options)
    {
        $this->calculator_options = $calculator_options;
    }
}
