<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

use Larium\Sale\AdjustableInterface;
use Larium\Sale\OrderInterface;
use Finite\StatefulInterface;
use Finite\Loader\ArrayLoader;
use Larium\StateMachine\Transition;
use Larium\StateMachine\StateMachine;

class Payment implements PaymentInterface, StatefulInterface
{
    protected $transactions;

    protected $amount;

    protected $source;

    protected $tag;

    protected $order;

    protected $payment_method;

    protected $state = 'unpaid';

    protected $state_machine;

    protected $states = array(
        'unpaid' => array(
            'type' => 'initial',
            'properties' => array()
        ),
        'authorized' => array(
            'type' => 'normal',
            'properties' => array()
        ),
        'paid' => array(
            'type' => 'final',
            'properties' => array()
        ),
        'refunded' => array(
            'type' => 'final',
            'properties' => array()
        )
    );

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

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    /* -(  StateMachine  ) ------------------------------------------------- */

    public function getFiniteState()
    {
        return $this->getState();
    }

    public function setFiniteState($state)
    {
        $this->setState($state);
    }

    public function processTo($state)
    {
        $this->getStateMachine()->apply($state);
    }

    public function getStateMachine()
    {
        if (null === $this->state_machine) {
            $data  = array(
                'class' => __CLASS__,
                'states' => $this->states,
            );

            $loader = new ArrayLoader($data);
            $this->state_machine = new StateMachine();
            $loader->load($this->state_machine);

            $this->transitions();

            $this->state_machine->setObject($this);
            $this->state_machine->initialize();
        }

        return $this->state_machine;
    }

    private function transitions()
    {
        $sm = $this->state_machine;

        $sm->addTransition(new Transition('purchase', array('unpaid'), 'purchased', array($this, 'toPurchase')));
        $sm->addTransition(new Transition('authorize', array('unpaid'), 'authorized', array($this, 'toAuthorized')));
        $sm->addTransition(new Transition('capture', array('authorized'), 'paid', array($this, 'toPaid')));
        $sm->addTransition(new Transition('void', array('authorized'), 'refunded', array($this, 'toRefunded')));
        $sm->addTransition(new Transition('credit', array('paid'), 'refunded', array($this, 'toRefunded')));
    }

    public function toPurchase(StateMachine $stateMachine, Transition $transition)
    {
        $action = $transition->getName();

        if (null === $this->getOrder()) {
            throw new \Exception("You must add this Payment to an Order.");
        }

        if (!$this->getOrder()->needsPayment()) {

            return;
        }

        $provider = $this->getPaymentMethod()->getProvider();

        if ($provider instanceof PaymentProviderInterface) {

            // The amount to charge for this payment.
            $amount = null === $this->amount
                ? $this->getOrder()->getTotalAmount()
                : $this->amount;

            // Invoke the method of Provider based on Transition name.
            $providerMethod = new \ReflectionMethod($provider, $action);
            $params = array(
                $amount,
                $this->getPaymentMethod()->getPaymentSource(),
                $this->options()
            );
            $response = $providerMethod->invokeArgs($provider, $params);

            if ($response->isSuccess()) {
                if (null === $this->amount) {
                    $this->setAmount($amount);
                }
            }

        } else {

            throw new Exception('Provider must implements Larium\Payment\PaymentProviderInterface');
        }
    }

    public function toAuthorized()
    {

    }

    public function toRefunded()
    {

    }

    protected function options()
    {
        $options = array();

        return $options;
    }
}
