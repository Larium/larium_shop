<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
namespace Larium\Shop\Sale;

use Larium\Shop\Store\Product;
use Larium\Shop\Payment\Payment;
use Larium\Shop\Payment\PaymentMethodInterface;
use Larium\Shop\Shipment\ShippingMethodInterface;
use Larium\Shop\Shipment\Shipment;
use Finite\State\State;
use Finite\StateMachine\StateMachine;
use Larium\Shop\StateMachine\ArrayLoader;
use Larium\Shop\StateMachine\TransitionListener;

/**
 * Cart
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Cart implements CartInterface
{
    /**
     * An order instance that belongs to this cart.
     *
     * @var Order
     * @access protected
     */
    protected $order;

    protected $state_machine;

    /**
     * {@inheritdoc}
     */
    public function addItem(OrderableInterface $orderable, $quantity=1)
    {
        $item = $this->item_from_orderable($orderable, $quantity);

        // Checks for duplicated item an increase quantity instead of adding.
        if ($order_item = $this->getOrder()->containsItem($item)) {

            $order_item->setQuantity(
                $order_item->getQuantity() + $item->getQuantity()
            );

            $order_item->calculateTotalPrice();

            return $order_item;
        }

        $this->getOrder()->addItem($item);

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItem $item)
    {
        $this->getOrder()->removeItem($item);
    }

    /**
     * Gets the Order that handle the Cart.
     * Creates new if does not exist.
     *
     * @access public
     * @return Order
     */
    public function getOrder()
    {
        if (null === $this->order) {
            $this->order = new Order();
            $this->initialize_state_machine();
        }

        return $this->order;
    }

    /**
     * Sets an Order to handle.
     *
     * @param  Order $order
     * @access public
     * @return void
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Gets a collection of order items
     *
     * @access public
     * @return array|Traversable
     */
    public function getItems()
    {
        return $this->getOrder()->getItems();
    }

    /**
     * Gets the total number of items in order.
     *
     * @access public
     * @return integer
     */
    public function getItemsCount()
    {
        $items = $this->getItems();

        if (is_array($items)) {
            return count($items);
        } else {
            return $items->count();
        }
    }

    /**
     * Delegate to Order.
     * Gets the total quantity of order items.
     *
     * @access public
     * @return integer
     */
    public function getTotalQuantity()
    {
        return $this->getOrder()->getTotalQuantity();
    }

    /**
     * {@inheritdoc}
     */
    public function addPaymentMethod(PaymentMethodInterface $method, $amount = null)
    {
        $payment = new Payment();

        $payment->setPaymentMethod($method);
        $payment->setAmount($amount);
        $this->getOrder()->addPayment($payment);

        return $payment;
    }

    /**
     * Applies the given state to Order.
     *
     * @param string $state
     * @access public
     * @return mixed
     */
    public function processTo($state)
    {
        return $this->state_machine->apply($state);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingMethod(ShippingMethodInterface $shipping_method)
    {
        $shipment = new Shipment();
        $shipment->setShippingMethod($shipping_method);
        $this->getOrder()->addShipment($shipment);

        return $shipment;
    }

    /**
     * Creates an OrderItem from a given Product.
     *
     * @param  OrderableInterface $orderable
     * @param  int                $quantity
     * @access protected
     * @return void
     */
    protected function item_from_orderable(
        OrderableInterface $orderable,
        $quantity = 1
    ) {
        $item = new OrderItem();
        $item->setOrderable($orderable);
        $item->setUnitPrice($orderable->getUnitPrice());
        $item->setQuantity($quantity);
        $item->setDescription($orderable->getDescription());

        return $item;
    }

    protected function initialize_state_machine()
    {
        $states = [
            self::CART       => ['type' => State::TYPE_INITIAL, 'properties' => []],
            self::CHECKOUT   => ['type' => State::TYPE_NORMAL, 'properties' => []],
            self::PARTIAL_PAID => ['type' => State::TYPE_NORMAL, 'properties' => []],
            self::PAID       => ['type' => State::TYPE_NORMAL, 'properties' => []],
            self::PROCESSING => ['type' => State::TYPE_NORMAL, 'properties' => []],
            self::SENT       => ['type' => State::TYPE_NORMAL, 'properties' => []],
            self::CANCELLED  => ['type' => State::TYPE_FINAL, 'properties' => []],
            self::DELIVERED  => ['type' => State::TYPE_FINAL, 'properties' => []],
            self::RETURNED   => ['type' => State::TYPE_FINAL, 'properties' => []],
        ];

        $transitions = [
            'checkout'  => ['from'=>[self::CART], 'to' => self::CHECKOUT],
            'partial_pay' => ['from'=>[self::PAID, SELF::PARTIAL_PAID], 'to' => self::PARTIAL_PAID],
            'pay'       => ['from'=>[self::CHECKOUT, SELF::PARTIAL_PAID], 'to' => self::PAID, 'do'=>[$this->order, 'processPayments'], 'if' => function ($sm){ return $sm->getObject()->needsPayment(); }],
            'process'   => ['from'=>[self::PAID], 'to' => self::PROCESSING],
            'send'      => ['from'=>[self::PROCESSING], 'to' => self::SENT],
            'deliver'   => ['from'=>[self::SENT],'to' => self::DELIVERED],
            'return'    => ['from'=>[self::SENT], 'to' => self::RETURNED],
            'cancel'    => ['from'=>[self::PAID, self::PROCESSING], 'to' => self::CANCELLED],
            'retry'     => ['from'=>[self::CANCELLED], 'to' => self::CHECKOUT],
        ];

        $loader = new ArrayLoader(
            [
                'class' => get_class($this->order),
                'states' => $states,
                'transitions' => $transitions
            ]
        );

        $this->state_machine = new StateMachine($this->order);

        $loader->load($this->state_machine);

        $event = new TransitionListener($this->state_machine->getDispatcher());

        $event->afterTransition('pay', array($this, 'rollbackPayment'));

        $this->state_machine->initialize();

    }

    /**
     * Gets state_machine.
     *
     * @access public
     * @return mixed
     */
    public function getStateMachine()
    {
        return $this->state_machine;
    }

    /**
     * Checks the balance of Order after a `pay` transition.
     * If balance is greater than zero then rollback to `checkout` state to
     * fullfil the payment of the Order.
     *
     * @access public
     * @return void
     */
    public function rollbackPayment()
    {
        if ($this->getOrder()->getBalance() > 0) {
            $this->processTo('partial_pay');
        }
    }
}
