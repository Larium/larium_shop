<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

use Larium\Sale\AdjustableInterface;
use Larium\Sale\OrderInterface;

class Payment implements PaymentInterface
{

    protected $transactions;

    protected $amount;

    protected $source;

    protected $tag;

    protected $order;

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

    public function getIdentifier()
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

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(PaymentMethod $payment_method)
    {
        $this->payment_method = $payment_method;
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
        return $this->getSource()->getDescription();
    }

    public function getCost()
    {
        return $this->getSource()->getCost();
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    public function detachOrder()
    {
        $this->order = null;
    }

    public function process()
    {
        if (null === $this->getOrder()) {
            throw new \Exception("You must set an Order for this Payment");
        }

        if (!$this->getOrder()->needsPayment()) {

            return;
        }
        
        $provider = $this->getPaymentMethod()->getProvider();

        if ($provider instanceof PaymentProviderInterface) {

            $amount = null === $this->amount 
                ? $this->getOrder()->getTotalAmount()
                : $this->amount;

            $response = $provider->purchase(
                $amount, 
                $this->getPaymentMethod()->getPaymentSource(),
                $this->options()
            );

            if (true === $response) {
                if (null === $this->amount) {
                    $this->setAmount($amount);
                }
            }

         } else {
             
             throw new Exception('Provider must implements Larium\Payment\PaymentProviderInterface');
         }
    }

    protected function options()
    {
        $options = array();

        
        return $options;
    }
}
