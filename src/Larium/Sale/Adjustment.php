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
     *
     * @var    mixed 
     * @access protected
     */
    protected $source;

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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */   
    public function setSource($source)
    {
        $this->source = $source;
    }
}
