<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\Payment\PaymentInterface;
use Finite\StatefulInterface;
use Finite\Loader\ArrayLoader;
use Larium\StateMachine\Transition;
use Larium\StateMachine\StateMachine;
use Larium\Shipment\ShippingInterface;

class Order implements OrderInterface, StatefulInterface
{
    protected $items;

    protected $adjustments;

    protected $payments;

    protected $shipping_method;

    protected $adjustments_total;

    protected $items_total;

    protected $total_amount;

    protected $total_payment_amount;

    protected $state;

    protected $state_machine;

    protected $states = array(
        'cart' => array(
            'type' => 'initial',
            'properties' => array()
        ),
        'checkout' => array(
            'type'  => 'normal',
            'properties' => array()
        ),
        'paid' => array(
            'type' => 'normal',
            'properties' => array()
        ),
        'processing' => array(
            'type'  => 'normal',
            'properties' => array()
        ),
        'sent' => array(
            'type' => 'normal',
            'properties' => array()
        ),
        'cancelled' => array(
            'type' => 'final',
            'properties' => array()
        ),
        'delivered' => array(
            'type' => 'final',
            'properties' => array()
        ),
        'returned' => array(
            'type' => 'final',
            'properties' => array()
        ),
    );

    public function __construct()
    {
        $this->initialize();
    }


    public function initialize()
    {
        $this->items        = new \SplObjectStorage();
        $this->adjustments  = new \SplObjectStorage();
        $this->payments     = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(OrderItemInterface $item)
    {
        $item->calculateTotalPrice();

        $item->generateIdentifier();

        $this->items->attach($item);

        $this->calculateTotalAmount();

        $item->setOrder($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItemInterface $item)
    {
        if ($remove = $this->contains($item)) {

            $this->items->detach($remove);

            $this->calculateTotalAmount();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function containsItem(OrderItemInterface $order_item)
    {
        foreach ($this->items as $item) {
            if ($item->getIdentifier() == $order_item->getIdentifier()) {
                return $item;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items)
    {
        foreach ($this->items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function calculateItemsTotal()
    {
        $total = 0;
        foreach ( $this->getItems() as $item) {
            $total += $item->getTotalPrice();
        }

        $this->items_total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsTotal()
    {
        return $this->items_total;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTotalAmount()
    {
        $this->calculateItemsTotal();

        $this->calculateAdjustmentsTotal();

        $this->total_amount = $this->items_total + $this->adjustments_total;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalAmount()
    {
        $this->calculateTotalAmount();

        return $this->total_amount;
    }

    public function addPayment(PaymentInterface $payment)
    {
        $this->payments->attach($payment);

        $payment->setOrder($this);

        $this->calculateTotalPaymentAmount();
    }

    /**
     * Checks if Order has Payment objects and returns the first.
     *
     * @access public
     * @return false|Larium\Payment\PaymentInterface
     */
    public function hasPayments()
    {
        if ($this->payments->count() > 0) {

            $this->payments->rewind();

            return $this->payments->current();
        }

        return false;
    }

    public function removePayment(PaymentInterface $payment)
    {

    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getTotalQuantity()
    {
        $quantity = 0;
        foreach ($this->getItems() as $item) {
            $quantity += $item->getQuantity();
        }

        return $quantity;
    }

    public function calculateTotalPaymentAmount()
    {
        $total = 0;

        foreach ($this->getPayments() as $payment) {
            $total += $payment->getAmount();
        }

        $this->total_payment_amount = $total;
    }

    public function getTotalPaymentAmount()
    {
        $this->calculateTotalPaymentAmount();

        return $this->total_payment_amount;
    }

    public function needsPayment()
    {
        return $this->getTotalAmount() > 0;
    }

    public function getBalance()
    {
        return $this->getTotalAmount() - $this->getTotalPaymentAmount();
    }

    /* -(  StateMachine  ) ------------------------------------------------- */

    public function process()
    {
        return $this->getStateMachine()->nextTransition();
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

    protected function transitions()
    {
        $sm = $this->state_machine;

        $sm->addTransition(new Transition('checkout', array('cart'), 'checkout'));
        $sm->addTransition(new Transition('pay', array('checkout'), 'paid', array($this, 'toPaid')));
        $sm->addTransition(new Transition('authorize', array('checkout'), 'authorized', array($this, 'toAuthorized')));
        $sm->addTransition(new Transition('process', array('paid'), 'processing'));
        $sm->addTransition(new Transition('send', array('processing'), 'sent'));
        $sm->addTransition(new Transition('deliver', array('sent'), 'delivered'));
        $sm->addTransition(new Transition('return', array('sent'), 'returned'));
        $sm->addTransition(new Transition('cancel', array('paid', 'processing'), 'cancelled'));
        $sm->addTransition(new Transition('retry', array('cancelled'), 'checkout'));
    }

    public function toPaid(StateMachine $stateMachine, Transition $transition)
    {
        $responses = array();

        foreach ($this->getPayments() as $payment) {
            $responses[] = $payment->processTo('purchase');
        }

        if (1 == count($responses)) {

            return current($responses);
        } else {

            return $responses;
        }
    }

    public function getFiniteState()
    {
        return $this->getState();
    }

    public function setFiniteState($state)
    {
        $this->setState($state);
    }

    /* -(  AdjustableInterface  ) ------------------------------------------ */

    /**
     * {@inheritdoc}
     */
    public function addAdjustment(AdjustmentInterface $adjustment)
    {
        $this->adjustments->attach($adjustment);

        $adjustment->setAdjustable($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAdjustment(AdjustmentInterface $adjustment)
    {
        $this->adjustments->detach($adjustment);

        $adjustment->detachAdjustable();
    }

    /**
     * {@inheritdoc}
     */
    public function containsAdjustment(AdjustmentInterface $adjustment)
    {
        foreach ($this->adjustments as $item) {
            if ($item->getIdentify() == $adjustment->getIdentify()) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustments()
    {
        return $this->adjustments;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateAdjustmentsTotal()
    {
        $total = 0;
        foreach ( $this->getAdjustments() as $item) {
            $total += $item->getAmount();
        }

        $this->adjustments_total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustmentsTotal()
    {

        $this->calculateAdjustmentsTotal();

        return $this->adjustments_total;
    }

    /**
     * Gets shipping_method.
     *
     * @access public
     * @return mixed
     */
    public function getShippingMethod()
    {
        return $this->shipping_method;
    }

    /**
     * Sets shipping_method.
     *
     * @param mixed $shipping_method the value to set.
     * @access public
     * @return void
     */
    public function setShippingMethod(ShippingInterface $shipping_method)
    {
        $this->shipping_method = $shipping_method;
    }
}
