<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

/**
 * OrderItem
 *
 * @uses OrderItemInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class OrderItem implements OrderItemInterface
{
    /**
     * unit_price
     *
     * @var Money\Money
     * @access protected
     */
    protected $unit_price;

    /**
     * quantity
     *
     * @var integer
     * @access protected
     */
    protected $quantity = 1;

    /**
     * total_price
     *
     * @var Money\Money
     * @access protected
     */
    protected $total_price;

    /**
     * description
     *
     * @var string
     * @access protected
     */
    protected $description;

    /**
     * order
     *
     * @var Larium\Shop\Sale\Order
     * @access protected
     */
    protected $order;

    /**
     * orderable
     *
     * @var Larium\Shop\Sale\OrderableInterface
     * @access protected
     */
    protected $orderable;

    /**
     * identifier
     *
     * @var string
     * @access protected
     */
    protected $identifier;

    /**
     * {@inheritdoc}
     */
    public function setUnitPrice($price)
    {
        $this->unit_price = $price;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function calculateTotalPrice()
    {
        $this->total_price = $this->getUnitPrice()->multiply($this->getQuantity());
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalPrice()
    {
        $this->calculateTotalPrice();

        return $this->total_price;
    }

    public function setTotalPrice($price)
    {
        $this->total_price = $price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setSku($sku)
    {
        $this->setIdentifier($sku);
    }

    public function getSku()
    {
        return $this->getIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($id)
    {
        $this->identifier = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        if (null == $this->identifier) {
            $this->generate_identifier();
        }

        return $this->identifier;
    }

    public function setOrderable(OrderableInterface $orderable)
    {
        $this->orderable = $orderable;

        $this->generate_identifier();
    }

    public function getOrderable()
    {
        return $this->orderable;
    }

    protected function generate_identifier()
    {
        if (null === $this->getOrderable()) {
            throw new \Exception("You must add an Orderable object before adding this item in Order");
        }

        $this->identifier = $this->getOrderable()->getSku();
    }
}
