<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

use Larium\Payment\Payment;

class Order implements OrderInterface 
{
    protected $status;

    protected $items;

    protected $adjustments;
    
    protected $payments;
    
    protected $adjustments_total;

    protected $items_total;

    protected $total_amount;
    
    protected $total_payment;

    public function __construct()
    {
        $this->items        = new \SplObjectStorage();
        $this->adjustments  = new \SplObjectStorage();
        $this->payments     = new \SplObjectStorage();
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
        
        $item->generateIdentifier();

        $this->items->attach($item);
        
        $this->calculateTotalAmount();

        $item->setOrder($this);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItemInterface $item)
    {
        $this->items->detach($item);
        
        $this->calculateTotalAmount();    
    }

    /**
     * {@inheritdoc}
     */
    public function containsItem(OrderItemInterface $order_item)
    {
        foreach ($this->items as $item) {
            if ($item->getIdentifier() == $order_item->getIdentifier()) {
                return $item;
            }
        }

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
        $total = 0;
        foreach ( $this->getItems() as $item) {
            $total += $item->getTotalPrice();
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
        $this->calculateItemsTotal();
        
        $this->calculateAdjustmentsTotal();

        $this->total_amount = $this->items_total + $this->adjustments_total; 
    }

    /**
     * {@inheritdoc}
     */   
    public function getTotalAmount()
    {
        $this->calculateTotalAmount();

        return $this->total_amount;
    }

    public function addPayment(PaymentInterface $payment)
    {
        $this->payments->attach($payment);
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getTotalQuantity()
    {
        $quantity = 0;
        foreach ($this->getItems() as $item) {
            $quantity += $item->getQuantity();
        }

        return $quantity;
    }

    public function getBalance()
    {
        return $total_amount - $total_payment;
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

        $adjustment->setAdjustable($this);
        $this->items->attach($adjustment, $type);
    }

    /**
     * {@inheritdoc}
     */ 
    public function removeAdjustment(AdjustmentInterface $adjustment)
    {
        $this->adjustments->detach($adjustment); 
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
        $total = 0;
        foreach ( $this->getAdjustments() as $item) {
            $total += $item->getAmount();
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
}
