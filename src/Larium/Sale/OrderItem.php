<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

class OrderItem implements OrderItemInterface
{
    protected $unit_price;

    protected $quantity=1;

    protected $total_price;

    protected $sku;

    protected $description;

    protected $order;

    protected $orderable;

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
        $this->total_price = $this->getQuantity() * $this->getUnitPrice();
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


    public function generateIdentifier()
    {
        if (null === $this->getOrderable()) {
            throw new \Exception("You must add an Orderable object before adding this item in Order");
        }
        
        $this->identifier = $this->getOrderable()->getSku();
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

}
