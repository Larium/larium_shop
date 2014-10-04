<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

use Larium\Shop\Sale\OrderInterface;
use Larium\Shop\Sale\OrderItemInterface;
use Larium\Shop\Common\Collection;

/**
 * Shipment
 *
 * @uses ShipmentInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Shipment implements ShipmentInterface
{
    /**
     * order
     *
     * @var Larium\Shop\Sale\Order
     * @access protected
     */
    protected $order;

    /**
     * address
     *
     * @var Larium\Shop\Shipment\Address
     * @access protected
     */
    protected $address;

    /**
     * shipping_method
     *
     * @var Larium\Shop\Shipment\ShippingMethod
     * @access protected
     */
    protected $shipping_method;

    /**
     * order_items
     *
     * @var Larium\Shop\Common\Collection
     * @access protected
     */
    protected $order_items;

    /**
     * cost
     *
     * @var Money\Money
     * @access protected
     */
    protected $cost;

    /**
     * identifier
     *
     * @var string
     * @access protected
     */
    protected $identifier;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->order_items = new Collection();

        $this->generate_identifier();
    }

    /**
     * {@inheritdoc }
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc }
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * {@inheritdoc }
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * {@inheritdoc }
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc }
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
        $this->cost = $this->getShippingMethod()->calculateCost($order);
        if ($this->cost->getAmount()) {
            $adj = new \Larium\Shop\Sale\Adjustment();
            $adj->setAmount($this->cost);
            $adj->setLabel($this->getIdentifier());

            $order->addAdjustment($adj);

            $order->calculateTotalAmount();
        }
    }

    /**
     * {@inheritdoc }
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc }
     */
    public function setAddress(AddressInterface $address)
    {
        $this->address = $address;
    }

    /**
     * {@inheritdoc }
     */
    public function getShippingMethod()
    {
        return $this->shipping_method;
    }

    /**
     * {@inheritdoc }
     */
    public function setShippingMethod(ShippingMethodInterface $shipping_method)
    {
        $this->shipping_method = $shipping_method;
    }

    /**
     * {@inheritdoc }
     */
    public function getOrderItems()
    {
        return $this->order_items;
    }

    /**
     * {@inheritdoc }
     */
    public function addOrderItem(OrderItemInterface $order_item)
    {
        $this->order_items->add($order_item);
    }

    /**
     * {@inheritdoc }
     */
    public function removeOrderItem(OrderItemInterface $order_item)
    {
        if ($item = $this->containsOrderItem($order_item)) {
            return $this->order_items->remove($item);
        }

        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function containsOrderItem(OrderItemInterface $order_item)
    {

        return $this->order_items->contains($order_item, function($item) use ($order_item){
            return $item->getIdentifier() == $order_item->getIdentifier();
        });

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function detachOrder()
    {
        $label = $this->getIdentifier();

        foreach ($this->order->getAdjustments() as $key => $a) {
            if ($label == $a->getLabel()) {
                $this->order->getAdjustments()->remove($a);
                $this->order = null;

                return true;
                break;
            }
        }

        return false;
    }

    /**
     * Generates a unique identifier for this shipment.
     *
     * @access protected
     * @return string
     */
    protected function generate_identifier()
    {
        $this->identifier = uniqid();
    }
}
