<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

class OrderItem implements OrderItemInterface
{
    protected $unit_price;

    protected $quantity=1;

    protected $order;

    protected $total_price;

    protected $type = OrderItemInterface::TYPE_PRODUCT;

    protected $sku;

    protected $description;

    protected $is_charge = true;
    
    protected $is_credit = false;

    public function setUnitPrice($price)
    {
        $this->setAmount($price);
    }

    public function getUnitPrice()
    {
        return $this->getAmount();
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setOrder(OrderInterface $order)
    {
        $this->setAdjustable($order);
    }
    
    public function getOrder()
    {
        return $this->getAdjustable();
    }

    /**
     * {@inheritdoc}
     */ 
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */ 
    public function getType()
    {
        return $this->type;
    }

    public function calculateTotalPrice()
    {
        $this->total_price = $this->getQuantity() * $this->getAmount();
    }

    /**
     * {@inheritdoc}
     */ 
    public function getTotalPrice()
    {
        $this->calculateTotalPrice();

        return $this->total_price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    /* -(  AdjustmentInterface  ) ------------------------------------------ */

    public function setAmount($amount)
    {
        $this->unit_price = $amount;
    }

    /**
     * {@inheritdoc}
     */  
    public function getAmount()
    {
        return $this->unit_price;
    }

    /**
     * {@inheritdoc}
     */ 
    public function isCharge()
    {
        return $this->is_charge;
    }

    /**
     * {@inheritdoc}
     */ 
    public function isCredit()
    {
        return $this->is_credit;
    }

    /**
     * {@inheritdoc}
     */ 
    public function setAdjustable(AdjustableInterface $adjustable)
    {
        $this->order = $adjustable;
    }

    /**
     * {@inheritdoc}
     */ 
    public function getAdjustable()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */ 
    public function setIdentify($id)
    {
        $this->sku = $id;
    }

    /**
     * {@inheritdoc}
     */ 
    public function getIdentify()
    {
        return $this->sku;
    }
}
