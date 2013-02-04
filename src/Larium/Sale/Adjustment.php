<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

class Adjustment implements AdjustmentIterface
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
     * Is adjustment a charge or a credit?
     * Allow values AdjustmentInterface::CHARGE or AdjustmentInterface::CREDIT.
     *
     * @var    int 
     * @access protected
     */
    protected $action;

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount
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
    public function isCharge() 
    {
        return  $this->action ===  AdjustableInterface::CHARGE;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredit() 
    {
        return  $this->action ===  AdjustableInterface::CREDIT;
    }
}
