<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

use Larium\Sale\AdjustableInterface;
use Larium\Sale\OrderInterface;
use Finite\StatefulInterface;
use Finite\Event\StateMachineEvent;
use Larium\StateMachine\EventTransition;
use Larium\StateMachine\StateMachineAwareInterface;
use Larium\StateMachine\StateMachineAwareTrait;

class Payment implements PaymentInterface, StatefulInterface, StateMachineAwareInterface
{
    use StateMachineAwareTrait;

    protected $transactions;

    protected $amount;

    protected $source;

    protected $tag;

    protected $order;

    protected $payment_method;

    protected $state = 'unpaid';

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->transactions = new \SplObjectStorage();
        $this->tag = uniqid();
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

    public function getStates()
    {
        return array(
            'unpaid' => array( 'type' => 'initial', 'properties' => array()),
            'authorized' => array('type' => 'normal','properties' => array()),
            'paid' => array('type' => 'final', 'properties' => array()),
            'refunded' => array('type' => 'final', 'properties' => array())
        );
    }

    public function getTransitions()
    {
        return array(
            'purchase' => array('from'=>array('unpaid'), 'to'=>'paid'),
            'authorize' => array('from'=>array('unpaid'), 'to'=>'authorized'),
            'capture' => array('from'=>array('authorized'), 'to'=>'paid'),
            'void' => array('from'=>array('authorized'), 'to'=>'refunded'),
            'credit' => array('from'=>array('paid'), 'to'=>'refunded'),
        );
    }

    public function setupEvents()
    {
        $this->event->beforeTransition('purchase', array($this, 'toPaid'));

    }

    public function toPaid(StateMachineEvent $event)
    {
        if (null === $this->getOrder()) {
            throw new \InvalidArgumentException("You must add this Payment to an Order.");
        }

        if (null === $this->getPaymentMethod()) {
            throw new \InvalidArgumentException("You must set a PaymentMethod for this Payment.");
        }

        // The amount to charge for this payment.
        $amount = $this->payment_amount();

        $response = $this->invoke_provider($amount, $event->getTransition()->getName());

        $this->create_transaction_from_response($response);

        if ($response->isSuccess()) {
            if (null === $this->amount) {
                $this->setAmount($amount);
            }

            return $response;
        }

        return false;

    }

    /**
     * Creates an adjustment for payment cost if needed and calculate the
     * amount for this payment.
     *
     * If Payment has received an amount then this amount will be used alse
     * will use the TotalAmount from Order.
     *
     * @access protected
     * @return void
     */
    protected function payment_amount()
    {
        $this->create_payment_method_adjustment();

        return null === $this->amount
            ? $this->getOrder()->getTotalAmount()
            : $this->amount;
    }

    protected function create_payment_method_adjustment()
    {

        if (null === $this->getPaymentMethod()) {

            return;
        }

        if ($cost = $this->getPaymentMethod()->getCost()) {
            $adj = new \Larium\Sale\Adjustment();
            $adj->setAmount($cost);
            $adj->setLabel($this->getPaymentMethod()->getTitle());
            $this->getOrder()->addAdjustment($adj);
        }
    }

    protected function invoke_provider($amount, $action)
    {
        $provider = $this->getPaymentMethod()->getProvider();

        if ($provider instanceof PaymentProviderInterface) {

            // Invoke the method of Provider based on Transition name.
            $providerMethod = new \ReflectionMethod($provider, $action);
            $params = array($amount, $this->options());
        } else {

            throw new Exception('Provider must implements Larium\Payment\PaymentProviderInterface');
        }
        return $providerMethod->invokeArgs($provider, $params);
    }

    /**
     * Additional options to pass to provider like billing / shipping address,
     * customer info, order number etc.
     *
     * @access protected
     * @return array
     */
    protected function options()
    {
        $options = array();

        return $options;
    }

    protected function create_transaction_from_response(Provider\Response $response)
    {

    }
}
