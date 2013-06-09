<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

use Larium\Sale\AdjustableInterface;
use Larium\Sale\Order;
use Larium\Sale\OrderItemInterface;

class Payment implements PaymentInterface
{

    protected $transactions;

    protected $amount;

    protected $resource;

    protected $tag;

    public function __construct()
    {
        $this->transactions = new \SplObjectStorage();
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getIdentify()
    {
        return $this->getTag();
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    public function addTransaction(TransactionInterface $transaction)
    {
        $this->getTransactions()->attach($transaction);
    }

    public function removeTransaction(TransactionInterface $transaction)
    {
        $this->getTransactions()->detach($transaction);
    }

    public function containsTransaction(TransactionInterface $transaction)
    {
        foreach ($this->getTransactions() as $trx) {
            if ($trx->getTransactionId() == $transaction->getTransactionId()) {
                
                return true;
            }
        }

        return false;
    }   

    public function getTotalTransactionsAmount()
    {
        $amount = 0;

        foreach ($this->getTransactions() as $trx) {
            $amount += $trx->getAmount();    
        }

        return $amount;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function setResource(PaymentResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getDescription()
    {
        return $this->getResource()->getDescription();
    }

    public function getCost()
    {
        return $this->getResource()->getCost();
    }
}
