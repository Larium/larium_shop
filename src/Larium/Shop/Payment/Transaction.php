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
namespace Larium\Shop\Payment;

/**
 * Transaction
 *
 * @uses TransactionInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class Transaction implements TransactionInterface
{
    /**
     * @var string
     */
    protected $transaction_id;

    /**
     * @var Larium\Shop\Payment\Payment
     */
    protected $payment;

    /**
     * amount
     *
     * @var int
     */
    protected $amount;

    /**
     * {@inheritdoc}
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayment(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;
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
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
