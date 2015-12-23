<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Sale;

/**
 * OrderableInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface OrderableInterface
{
    /**
     * Gets the price of a unit.
     *
     * @access public
     * @return float|integer
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
     * Usefull when preview Order Items without the need to load Orderable
     * resource.
     *
     * @access public
     * @return string
     */
    public function getDescription();
}
