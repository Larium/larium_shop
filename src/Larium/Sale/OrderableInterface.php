<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Sale;

interface OrderableInterface
{
    /**
     * Gets the price of a unit of this product.
     *
     * @access public
     * @return number
     */
    public function getUnitPrice();

    /**
     * Gets the title of the product.
     *
     * @access public
     * @return string 
     */
    public function getTitle(); 
}
