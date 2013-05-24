<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

interface OrderItemInterface extends AdjustmentInterface
{
    const TYPE_PRODUCT  = 1;
    const TYPE_SHIPPING = 2;
    const TYPE_BILLING  = 3;
    const TYPE_DISCOUNT = 4;

    public function setUnitPrice($price);

    public function getUnitPrice();

    /**
     * Sets the quantity of item to be ordered. 
     * 
     * @access public
     * @return void
     */
    public function setQuantity($quantity);
    
    /**
     * Gets the quantity of item to be ordered. 
     * 
     * @access public
     * @return integer
     */
    public function getQuantity();

    /**
     * Sets the order object to this item.
     * 
     * @param  OrderInterface $order 
     * @access public
     * @return void
     */
    public function setOrder(OrderInterface $order);
    
    /**
     * Gets the order object of this item.
     * 
     * @access public
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * Gets the type of an item in order.
     * 
     * @access public
     * @return integer
     */
    public function getType();
    
    /**
     * Sets the type of this item.
     * Can be one of 
     *  self::TYPE_PRODUCT
     *  self::TYPE_SHIPPING
     *  self::TYPE_BILLING
     *  self::TYPE_DISCOUNT
     * 
     * @access public
     * @return void
     */
    public function setType($type);

    /**
     * Returns the total amount of item based on price per quantity and on 
     * Adjustments total amount.
     * 
     * @access public
     * @return number
     */
    public function getTotalPrice();

    public function getDescription();

    public function setDescription($description);
}
