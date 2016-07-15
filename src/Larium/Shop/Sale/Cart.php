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

use Finite\State\State;
use Finite\StateMachine\StateMachine;
use Finite\Loader\ArrayLoader;
use Larium\Shop\Store\Product;
use Larium\Shop\Payment\Payment;
use Larium\Shop\Payment\PaymentMethodInterface;
use Larium\Shop\Shipment\ShippingMethodInterface;
use Larium\Shop\Shipment\Shipment;

/**
 * Cart
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Cart implements CartInterface
{
    /**
     * An order instance that belongs to this cart.
     *
     * @var Order
     */
    protected $order;

    /**
     * @var Finite\StateMachine\StateMachine
     */
    protected $state_machine;

    public function __construct(OrderInterface $order = null)
    {
        if ($this->order = $order) {
            $this->initializeStateMachine();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(OrderableInterface $orderable, $quantity = 1)
    {
        $item = $this->itemFromOrderable($orderable, $quantity);

        // Checks for duplicated item and increase quantity instead of adding.
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
     * {@inheritdoc}
     */
    public function getOrder()
    {
        if (null === $this->order) {
            $this->order = new Order();
            $this->initializeStateMachine();
        }

        return $this->order;
    }

    /**
     * Sets an Order to handle.
     *
     * @param  Order $order
     * @return void
     */
    private function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Gets a collection of order items
     *
     * @return array|Traversable
     */
    public function getItems()
    {
        return $this->getOrder()->getItems();
    }

    /**
     * Gets the total number of items in order.
     *
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
     * @return integer
     */
    public function getTotalQuantity()
    {
        return $this->getOrder()->getTotalQuantity();
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentMethod(
        PaymentMethodInterface $method,
        $amount = null
    ) {
        $payment = new Payment(
            $this->getOrder(),
            $method,
            $amount
        );

        return $payment;
    }

    /**
     * Applies the given state to Order.
     *
     * @param string $state
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
     * @return OrderItem
     */
    protected function itemFromOrderable(
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

    protected function initializeStateMachine()
    {
        $reflection = new OrderStateReflection();

        $config = $reflection->getStateConfig();

        $loader = new ArrayLoader($config);

        $this->state_machine = new StateMachine($this->order);

        $loader->load($this->state_machine);

        $this->state_machine->initialize();

    }

    /**
     * Gets state machine instance.
     *
     * @return StateMachine
     */
    private function getStateMachine()
    {
        return $this->state_machine;
    }
}
