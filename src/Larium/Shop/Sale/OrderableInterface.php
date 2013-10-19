<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

interface OrderableInterface
{
    /**
     * Gets the price of a unit.
     *
     * @access public
     * @return number
     */
    public function getUnitPrice();

    /**
     * Gets the unique sku number.
     *
     * @access public
     * @return string
     */
    public function getSku();


    /**
     * Gets a description of Orderable.
     *
     * Usefull when preview Order Items whithout the need to load Orderable
     * resource.
     *
     * @access public
     * @return void
     */
    public function getDescription();
}
