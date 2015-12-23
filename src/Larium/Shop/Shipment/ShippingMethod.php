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
namespace Larium\Shop\Shipment;

use Larium\Shop\Sale\OrderInterface;

/**
 * ShippingMethod
 *
 * @uses ShippingMethodInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class ShippingMethod implements ShippingMethodInterface
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $calculator_class;

    /**
     * @var Larium\Shop\Calculator\AbstractCalculator
     */
    protected $calculator;

    /**
     * @var array
     */
    protected $calculator_options;

    /**
     * Gets label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets label.
     *
     * @param string $label
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
     * @return number
     */
    public function calculateCost(OrderInterface $order = null)
    {
        return $this->getCalculator()->compute($order);
    }

    /**
     * Gets a Calclulator instance to compute shipping costs.
     *
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
     * @return void
     */
    public function setCalculatorClass($calculator_class)
    {
        $this->calculator_class = $calculator_class;
    }

    /**
     * Gets an options array for instantiate a calculator class.
     *
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
     * @return void
     */
    public function setCalculatorOptions(array $calculator_options)
    {
        $this->calculator_options = $calculator_options;
    }
}
