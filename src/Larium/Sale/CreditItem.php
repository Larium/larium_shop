<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

class CreditItem extends OrderItem
{
    protected $type = OrderItemInterface::TYPE_DISCOUNT;

    protected $sku = 'Discount #1';

    protected $is_charge = false;
    
    protected $is_credit = true;

    /**
     * {@inheritdoc}
     */  
    public function getAmount()
    {
        if ($this->isCredit()) {
            return -$this->unit_price;
        }
        return $this->unit_price;
    }
}
