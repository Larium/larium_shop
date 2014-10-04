<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

class Transaction implements TransactionInterface
{
    /**
     * transaction_id
     *
     * @var string
     * @access protected
     */
    protected $transaction_id;

    /**
     * payment
     *
     * @var Larium\Shop\Payment\Payment
     * @access protected
     */
    protected $payment;

    /**
     * amount
     *
     * @var Money\Money
     * @access protected
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
