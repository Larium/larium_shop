<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

/**
 * Adjustment
 *
 * @uses AdjustmentInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Adjustment implements AdjustmentInterface
{
    /**
     * The amount of Adjustement.
     *
     * @var    number
     * @access protected
     */
    protected $amount;

    /**
     * The Adjustable object
     *
     * @var    AdjustableInterface
     * @access protected
     */
    protected $adjustable;

    /**
     * The display label.
     *
     * @var string
     * @access protected
     */
    protected $label;

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustable(AdjustableInterface $adjustable)
    {
        $this->adjustable = $adjustable;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustable()
    {
        return $this->adjustable;
    }

    /**
     * {@inheritdoc}
     */
    public function detachAdjustable()
    {
        $this->adjustable = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isCharge()
    {
        return $this->getAmount() >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredit()
    {
        return $this->getAmount() < 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
}
