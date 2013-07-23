<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
namespace Larium\Sale;

use Larium\Store\Product;
use Larium\Payment\Payment;
use Larium\Payment\PaymentMethodInterface;

class Cart
{
    /**
     * An order instance that belongs to this cart.
     *
     * @var Order
     * @access protected
     */
    protected $order;

    /**
     * Add an Orderable class to the Order.
     *
     * @param  OrderableInterface $orderable
     * @param  int                $quantity
     * @access public
     * @return OrderItem
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
     * Removes an Orderitem from Order
     *
     * @param  OrderItem $item
     * @access public
     * @return void
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
     * Creates and adds a Payment to order based on PaymentMethod.
     *
     * Returns the Payment instance.
     *
     * @param PaymentMethodInterface $method
     * @access public
     * @return Larium\Payment\PaymentInterface
     */
    public function addPaymentMethod(PaymentMethodInterface $method, $amount=null)
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
        return $this->getOrder()->getStateMachine()->apply($state);
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
        $quantity=1
    ) {
        $item = new OrderItem();
        $item->setOrderable($orderable);
        $item->setUnitPrice($orderable->getUnitPrice());
        $item->setQuantity($quantity);
        $item->setDescription($orderable->getDescription());

        return $item;
    }
}
