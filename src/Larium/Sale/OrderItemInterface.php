<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

interface OrderItemInterface
{
    public function setUnitPrice($price);

    public function getUnitPrice();

    public function setQuantity($quantity);
    
    public function getQuantity();

    public function setOrder(OrderInterface $order);
    
    public function getOrder();   

    /**
     * Returns the total amount of item based on price per quantity and on 
     * Adjustments total amount.
     * 
     * @access public
     * @return number
     */
    public function getTotalAmount();
}
