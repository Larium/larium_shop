<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

use Larium\Shop\Sale\OrderableInterface;

/**
 * Variant
 *
 * @uses OrderableInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Variant implements OrderableInterface
{
    protected $sku;

    protected $unit_price;

    protected $stock_units;

    protected $is_default;

    protected $product;

    /**
     * Get is_default.
     *
     * @return is_default.
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Set is_default.
     *
     * @param is_default the value to set.
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;
    }

    /**
     * Get sku.
     *
     * @return sku.
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set sku.
     *
     * @param sku the value to set.
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * Get unit_price.
     *
     * @return unit_price.
     */
    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    /**
     * Set unit_price.
     *
     * @param unit_price the value to set.
     */
    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getDescription()
    {
        return $this->getProduct()->getTitle();
    }

    /**
     * Get stock_units.
     *
     * @return stock_units.
     */
    public function getStockUnits()
    {
        return $this->stock_units;
    }

    /**
     * Set stock_units.
     *
     * @param stock_units the value to set.
     */
    public function setStockUnits($stock_units)
    {
        $this->stock_units = $stock_units;
    }

    /**
     * Get product.
     *
     * @return product.
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product.
     *
     * @param product the value to set.
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
}
