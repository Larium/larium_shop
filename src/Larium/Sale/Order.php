<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

class Order implements OrderInterface 
{
    private $status;

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
        $this->getItems()->add($item);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItemInterface $item)
    {
        $this->getItems()->remove($item);
    }
    
    public function getItems()
    {
        return $this->getOrderItems();
    }
}
