<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

use Finite\StatefulInterface;
use Finite\StateMachine\StateMachine;
use Finite\State\State;
use Finite\Loader\ArrayLoader;
use Finite\Event\TransitionEvent;
use Larium\Shop\Payment\PaymentInterface;
use Larium\Shop\Shipment\ShipmentInterface;
use Larium\Shop\Common\Collection;

/**
 * Order class
 *
 * @uses OrderInterface
 * @uses StatefulInterface
 * @uses StateMachineAwareInterface
 * @author Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Order implements OrderInterface, StatefulInterface
{
    /**
     * Order items.
     *
     * @var Collection
     * @access protected
     */
    protected $items;

    /**
     * Order adjustments
     *
     * @var Collection
     * @access protected
     */
    protected $adjustments;

    /**
     * Order payments
     *
     * @var Collection
     * @access protected
     */
    protected $payments;

    /**
     * Order current processed payment
     *
     * @var PaymentInterface
     * @access protected
     */
    protected $current_payment;

    /**
     * Order shipments
     *
     * @var Collection
     * @access protected
     */
    protected $shipments;

    /**
     * Order adjustments total amount
     *
     * @var float
     * @access protected
     */
    protected $adjustments_total;

    /**
     * Order total items amount
     *
     * @var float
     * @access protected
     */
    protected $items_total;

    /**
     * Order total amount
     *
     * @var float
     * @access protected
     */
    protected $total_amount;

    /**
     * Order total payment amount
     *
     * @var float
     * @access protected
     */
    protected $total_payment_amount;

    /**
     * Order current state.
     * @see Order::getStates method for a listo of available states.
     *
     * @var string
     * @access protected
     */
    protected $state;

    protected $state_machines = array();

    public function __construct()
    {
        $this->initialize();
    }


    public function initialize()
    {
        $this->items        = new Collection();
        $this->adjustments  = new Collection();
        $this->payments     = new Collection();
        $this->shipments    = new Collection();
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

        $this->items->add($item);

        $this->calculateTotalAmount();

        $item->setOrder($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItemInterface $item)
    {
        if ($remove = $this->containsItem($item)) {

            $this->items->remove($remove);

            $this->calculateTotalAmount();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function containsItem(OrderItemInterface $order_item)
    {
        return $this->items->contains($order_item, function($item) use ($order_item){
            return $item->getIdentifier() == $order_item->getIdentifier();
        });

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
    public function addShipment(ShipmentInterface $shipment)
    {
        $this->shipments->add($shipment);

        foreach ($this->getItems() as $item) {
            $shipment->addOrderItem($item);
        }

        $shipment->setOrder($this);
    }

    /**
     * Gets all assigned shipments for this Order.
     *
     * @access public
     * @return Collection
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * {@inheritdoc}
     */
    public function removeShipment(ShipmentInterface $shipment)
    {
        $removed = $this->shipments->remove($shipment, function($s) use ($shipment) {
            return $shipment->getIdentifier() === $s->getIdentifier();
        });

        if ($removed) {
            $shipment->detachOrder($this);
            $this->calculateTotalAmount();
        }

        return $removed != null;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCost()
    {
        $amount = 0;
        foreach ($this->shipments as $shipment) {
            $amount += $shipment->getCost();
        }

        return $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function addPayment(PaymentInterface $payment)
    {
        $this->payments->add($payment);

        $payment->setOrder($this);

        $this->calculateTotalPaymentAmount();

        $this->initialize_state_machine($payment);
    }

    /**
     * {@inheritdoc}
     */
    public function removePayment(PaymentInterface $payment)
    {
        $removed = $this->payments->remove($payment, function($p) use ($payment) {
            return $payment->getIdentifier() === $p->getIdentifier();
        });

        if ($removed) {
            $payment->detachOrder($this);

            foreach ($this->state_machines as $key => $sm) {
                if ($sm->getObject()->getIdentifier() === $payment->getIdentifier()) {
                    unset($this->state_machines[$key]);
                }
            }
        }

        return $removed != null;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Sets the Payment that currently is processed.
     *
     * @param PaymentInterface $payment
     * @access public
     * @return void
     */
    public function setCurrentPayment(PaymentInterface $current_payment)
    {
        $this->current_payment = $current_payment;
    }

    /**
     * Returns the payment that currently is processed.
     *
     * @access public
     * @return PaymentInterfacce
     */
    public function getCurrentPayment()
    {
        return $this->current_payment;
    }

    /**
     * Unsets the current Payment.
     *
     * @access public
     * @return void
     */
    public function unsetCurrentPayment()
    {
        $this->current_payment = null;
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

    /**
     * Checks if Order needs payment.
     *
     * @access public
     * @return boolean
     */
    public function needsPayment()
    {
        return $this->getBalance() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        return $this->getTotalAmount() - $this->getTotalPaymentAmount();
    }

    public function processPayments(Order $order, TransitionEvent $event)
    {
        if (!$this->needsPayment()) {
            return;
        }

        foreach ($this->state_machines as $sm) {

            $payment = $sm->getObject();

            $state = $payment->getState();

            if ('unpaid' === $state || 'in_progress' === $state) {

                $this->setCurrentPayment($payment);

                if ('unpaid' === $state) {
                    $response = $sm->apply('purchase');
                } elseif ('in_progress' === $state) {
                    $response = $sm->apply('doPurchase');
                }

                return $response;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFiniteState()
    {
        return $this->getState();
    }

    /**
     * {@inheritdoc}
     */
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
        $this->adjustments->add($adjustment);

        $adjustment->setAdjustable($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAdjustment(AdjustmentInterface $adjustment)
    {
        $this->adjustments->remove($adjustment, function($a) use ($adjustment){
            return $a->getLabel() === $adjustment->getLabel();
        });

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

    public function initialize_state_machine($payment)
    {
        $states = [
            'unpaid'     => ['type' => State::TYPE_INITIAL, 'properties' => []],
            'in_process' => ['type' => State::TYPE_NORMAL,  'properties' => []],
            'authorized' => ['type' => State::TYPE_NORMAL,  'properties' => []],
            'paid'       => ['type' => State::TYPE_FINAL,   'properties' => []],
            'refunded'   => ['type' => State::TYPE_FINAL,   'properties' => []]
        ];


        $transitions = [
            'purchase'      => ['from'=>['unpaid'], 'to'=>'paid'],
            'doPurchase'    => ['from'=>['in_progress'], 'to'=>'paid'],
            'doAuthorize'   => ['from'=>['in_progress'], 'to'=>'authorize'],
            'authorize'     => ['from'=>['unpaid'], 'to'=>'authorized'],
            'capture'       => ['from'=>['authorized'], 'to'=>'paid'],
            'void'          => ['from'=>['authorized'], 'to'=>'refunded'],
            'credit'        => ['from'=>['paid'], 'to'=>'refunded'],
        ];

        $callbacks = [
            'after' => [
                [
                    'from' => ['unpaid', 'in_progress'],
                    'to'   => 'paid',
                    'do'   => [$payment, 'toPaid']
                ]
            ]
        ];

        $loader = new ArrayLoader(
            [
                'class' => get_class($payment),
                'states' => $states,
                'transitions' => $transitions,
                'callbacks' => $callbacks
            ]
        );

        $state_machine = new StateMachine($payment);

        $loader->load($state_machine);

        $state_machine->initialize();

        $this->state_machines[] = $state_machine;
    }
}
