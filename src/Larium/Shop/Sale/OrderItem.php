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

/**
 * OrderItem
 *
 * @uses OrderItemInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class OrderItem implements OrderItemInterface
{
    /**
     * @var Money\Money
     */
    protected $unit_price;

    /**
     * @var integer
     */
    protected $quantity = 1;

    /**
     * @var Money\Money
     */
    protected $total_price;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var Larium\Shop\Sale\Order
     */
    protected $order;

    /**
     * @var Larium\Shop\Sale\OrderableInterface
     */
    protected $orderable;

    /**
     * @var string
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
            $this->generateIdentifier();
        }

        return $this->identifier;
    }

    public function setOrderable(OrderableInterface $orderable)
    {
        $this->orderable = $orderable;

        $this->generateIdentifier();
    }

    public function getOrderable()
    {
        return $this->orderable;
    }

    protected function generateIdentifier()
    {
        if (null === $this->getOrderable()) {
            throw new \Exception("You must add an Orderable object before adding this item in Order");
        }

        $this->identifier = $this->getOrderable()->getSku();
    }
}
