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
namespace Larium\Shop\Sale;

use Money\Money;

/**
 * Adjustment
 *
 * @uses AdjustmentInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Adjustment implements AdjustmentInterface
{
    /**
     * The amount of Adjustement.
     *
     * @var   Money\Money
     */
    protected $amount;

    /**
     * The Adjustable object
     *
     * @var    Larium\Shop\Sale\AdjustableInterface
     */
    protected $adjustable;

    /**
     * The display label.
     *
     * @var string
     */
    protected $label;

    /**
     * {@inheritdoc}
     */
    public function setAmount(Money $amount)
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
