<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\Payment\PaymentInterface;
use Finite\StatefulInterface;
use Finite\Event\StateMachineEvent;
use Larium\StateMachine\EventTransition;
use Larium\StateMachine\StateMachineAwareInterface;
use Larium\StateMachine\StateMachineAwareTrait;
use Larium\Shipment\ShippingInterface;

class Order implements OrderInterface, StatefulInterface, StateMachineAwareInterface
{
    use StateMachineAwareTrait;

    protected $items;

    protected $adjustments;

    protected $payments;

    protected $current_payment;

    protected $shipping_method;

    protected $adjustments_total;

    protected $items_total;

    protected $total_amount;

    protected $total_payment_amount;

    protected $state;

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

    /**
     * {@inheritdoc}
     */
    public function getTotalQuantity()
    {
        $quantity = 0;
        foreach ($this->getItems() as $item) {
            $quantity += $item->getQuantity();
        }

        return $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function addPayment(PaymentInterface $payment)
    {
        $this->payments->attach($payment);

        $payment->setOrder($this);

        $this->calculateTotalPaymentAmount();
    }

    /**
     * {@inheritdoc}
     */
    public function removePayment(PaymentInterface $payment)
    {
        foreach ($this->payments as $p) {
            if ($payment->getIdentifier() === $p->getIdentifier()) {
                $this->payment->detach($payment);
                $payment->detachOrder($this);
            }
        }
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function calculateTotalPaymentAmount()
    {
        $total = 0;

        foreach ($this->getPayments() as $payment) {
            if ($payment->getState() == 'paid') {
                $total += $payment->getAmount();
            }
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

    public function getStates()
    {
        return array(
            'cart' => array('type' => 'initial', 'properties' => array()),
            'checkout' => array('type' => 'normal', 'properties' => array()),
            'paid' => array('type' => 'normal', 'properties' => array()),
            'processing' => array('type' => 'normal', 'properties' => array()),
            'sent' => array('type' => 'normal', 'properties' => array()),
            'cancelled' => array('type' => 'final', 'properties' => array()),
            'delivered' => array('type' => 'final', 'properties' => array()),
            'returned' => array('type' => 'final', 'properties' => array()),
        );
    }

    public function getTransitions()
    {
        return array(
            'checkout' => array('from'=>['cart'], 'to'=>'checkout'),
            'pay' => array('from'=>['checkout'], 'to'=>'paid'),
            'process' => array('from'=>['paid'], 'to'=>'processing'),
            'send' => array('from'=>['processing'], 'to'=>'sent'),
            'deliver' => array('from'=>['sent'],'to'=>'delivered'),
            'return' => array('from'=>['sent'], 'to'=>'returned'),
            'cancel' => array('from'=>array('paid', 'processing'), 'to'=>'cancelled'),
            'retry' => array('from'=>['cancelled'], 'to'=>'checkout'),
        );
    }

    public function setupEvents()
    {
        if ($this->needsPayment()) {
            $this->event->beforeTransition('pay', array($this, 'processPayments'));
        }

        $this->event->afterTransition('pay', array($this, 'rollbackPayment'));

    }

    public function processPayments(StateMachineEvent $event)
    {

        foreach ($this->payments as $payment) {
            if (   'unpaid' === $payment->getState()
                && $this->getBalance() > 0
            ) {

                $payment->processTo('purchase');
            }
        }
    }

    public function rollbackPayment()
    {
        if ($this->getBalance() > 0) {
            $this->state = 'checkout';
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
