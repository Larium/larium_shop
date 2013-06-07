<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\CallbackFilterIterator;

class Order implements OrderInterface 
{
    protected $status;

    protected $items;

    protected $total_amount;
    
    protected $items_total;

    public function __construct()
    {
        $this->items = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */   
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(OrderItemInterface $item)
    {
        $item->calculateTotalPrice();

        $this->addAdjustment($item);
        
        $this->calculateTotalAmount();

        $item->setOrder($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItemInterface $item)
    {
        $this->removeAdjustment($item);
        
        $this->calculateTotalAmount();    
    }

    /**
     * {@inheritdoc}
     */
    public function containsItem(OrderItemInterface $item)
    {
        return $this->containsAdjustment($item);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->getAdjustments();
    }


    /**
     * Gets only adjustments that have the type of Product. 
     * 
     * @access public
     * @return Iterator
     */
    public function getProductItems()
    {
        return $this->filter_by_type(OrderItemInterface::TYPE_PRODUCT);
    }


    public function getCreditItems()
    {
        return $this->filter_by_type(OrderItemInterface::TYPE_DISCOUNT);
    }

    public function getShippingItems()
    {
        return $this->filter_by_type(OrderItemInterface::TYPE_SHIPPING);
    }

    public function getBillingItems()
    {
        return $this->filter_by_type(OrderItemInterface::TYPE_BILLING);
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
    public function calculateProductsTotal()
    {
        $total = 0;
        foreach ( $this->getProductItems() as $item) {
            $total += $item->getTotalPrice();
        }

        $this->items_total = $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductsTotal()
    {
        return $this->items_total;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateTotalAmount()
    {
        $this->calculateProductsTotal();
        
        $this->calculateAdjustmentsTotal();
        
    }

    /**
     * {@inheritdoc}
     */   
    public function getTotalAmount()
    {
        return $this->getAdjustmentsTotal();
    }
    
    /* -(  AdjustableInterface  ) ------------------------------------------ */

    /**
     * {@inheritdoc}
     */ 
    public function addAdjustment(AdjustmentInterface $adjustment)
    {
        $type = $adjustment->isCharge() 
            ? AdjustmentInterface::CHARGE
            : AdjustmentInterface::CREDIT;

        $this->items->attach($adjustment, $type);
    }

    /**
     * {@inheritdoc}
     */ 
    public function removeAdjustment(AdjustmentInterface $adjustment)
    {
        $this->items->detach($adjustment); 
    }

    /**
     * {@inheritdoc}
     */ 
    public function containsAdjustment(AdjustmentInterface $adjustment)
    {
        return $this->items->contains($adjustment);
    }

    /**
     * {@inheritdoc}
     */ 
    public function getAdjustments()
    {
        return $this->items; 
    }

    /**
     * {@inheritdoc}
     */ 
    public function calculateAdjustmentsTotal()
    {
        $total = 0;
        foreach ( $this->getAdjustments() as $item) {
            $total += $item->getTotalPrice();
        }

        $this->total_amount = $total;
    }
    
    /**
     * {@inheritdoc}
     */ 
    public function getAdjustmentsTotal()
    {

        $this->calculateAdjustmentsTotal();

        return $this->total_amount;   
    }


    protected function filter_by_type($type)
    {
        return new CallbackFilterIterator(
            $this->items,
            function ($current, $key, $iterator) use ($type) {
                return $current->getType() == $type; 
            }
        );        
    }
}
