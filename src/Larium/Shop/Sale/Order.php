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
namespace Larium\Shop\Sale;

use Money\Money;
use Finite\State\State;
use Finite\StatefulInterface;
use Finite\Loader\ArrayLoader;
use Finite\Event\TransitionEvent;
use Larium\Shop\Common\Collection;
use Finite\StateMachine\StateMachine;
use Larium\Shop\Payment\PaymentInterface;
use Larium\Shop\Shipment\ShipmentInterface;
use Larium\Shop\Payment\PaymentStateReflection;

/**
 * Order class
 *
 * @uses OrderInterface
 * @uses StatefulInterface
 * @uses StateMachineAwareInterface
 * @author Andreas Kollaros <andreas@larium.net>
 */
class Order implements OrderInterface, StatefulInterface
{
    /**
     * Order items.
     *
     * @var Larium\Shop\Common\Collection
     */
    protected $items;

    /**
     * Order adjustments
     *
     * @var Larium\Shop\Common\Collection
     */
    protected $adjustments;

    /**
     * Order payment
     *
     * @var Larium\Shop\Payment\Payment
     */
    protected $payment;

    /**
     * Order current processed payment
     *
     * @var Larium\Shop\Payment\Payment
     */
    protected $current_payment;

    /**
     * Order shipments
     *
     * @var Larium\Shop\Common\Collection
     */
    protected $shipments;

    /**
     * Order adjustments total amount
     *
     * @var Money\Money
     */
    protected $adjustments_total;

    /**
     * Order total items amount
     *
     * @var Money\Money
     */
    protected $items_total;

    /**
     * Order total amount
     *
     * @var Money\Money
     */
    protected $total_amount;

    /**
     * Order total payment amount
     *
     * @var Money\Money
     */
    protected $total_payment_amount;

    /**
     * Order current state.
     * @see Order::getStates method for a list of available states.
     *
     * @var string
     */
    protected $state;

    /**
     * state_machines
     *
     * @var array
     * @access protected
     */
    protected $state_machine;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->items = new Collection();
        $this->shipments = new Collection();
        $this->adjustments = new Collection();
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
        return $this->items->contains($order_item, function ($item) use ($order_item) {
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
        $total = Money::EUR(0);
        foreach ($this->getItems() as $item) {
            $total = $total->add($item->getTotalPrice());
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
        $total = Money::EUR(0);
        $this->calculateItemsTotal();

        $this->calculateAdjustmentsTotal();

        $total = $total->add($this->items_total);

        $total = $total->add($this->adjustments_total);

        $this->total_amount = $total;
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
        $removed = $this->shipments->remove($shipment, function ($s) use ($shipment) {
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
        $amount = Money::EUR(0);
        foreach ($this->shipments as $shipment) {
            $amount = $amount->add($shipment->getCost());
        }

        return $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayment(PaymentInterface $payment)
    {
        $this->payment = $payment;

        $this->calculateTotalPaymentAmount();
    }

    /**
     * {@inheritdoc}
     */
    public function removePayment()
    {
        $this->payment->detachOrder($this);

        $this->state_machine = null;
        $this->payment = null;

        return true;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function calculateTotalPaymentAmount()
    {
        if (null == $this->payment) {
            return;
        }

        $total = Money::EUR(0);

        if ($this->payment->getState() == PaymentInterface::PAID) {
            $total = $total->add($this->payment->getAmount());
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
        return $this->getBalance()->isPositive();
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        return $this->getTotalAmount()->subtract($this->getTotalPaymentAmount());
    }

    public function processPayment()
    {
        if (!$this->needsPayment()) {
            return;
        }

        $this->initializeStateMachine($this->payment);
        $sm = $this->state_machine;
        $state = $this->payment->getState();

        if ('unpaid' === $state || 'pending' === $state) {
            if ('unpaid' === $state) {
                $response = $sm->apply('purchase');
            } elseif ('pending' === $state) {
                $response = $sm->apply('doPurchase');
            }

            return $response;
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
        $this->adjustments->remove($adjustment, function ($a) use ($adjustment) {
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
        $total = Money::EUR(0);
        foreach ($this->getAdjustments() as $item) {
            $total = $total->add($item->getAmount());
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

    public function initializeStateMachine(StatefulInterface $payment)
    {
        $reflection = new PaymentStateReflection();

        $config = $reflection->getStateConfig();

        $loader = new ArrayLoader($config);

        $state_machine = new StateMachine($payment);

        $loader->load($state_machine);

        $state_machine->initialize();

        $this->state_machine = $state_machine;
    }
}
