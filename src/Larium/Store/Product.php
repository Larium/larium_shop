<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Store;

use Larium\Sale\OrderableInterface;

class Product implements OrderableInterface
{
    protected $sku;

    protected $title;

    protected $description;

    protected $permalink;

    protected $meta_description;

    protected $meta_keywords;

    protected $available_on;

    protected $created_at;

    protected $updated_at;

    protected $deleted_at;

    protected $unit_price;

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setUnitPrice($price)
    {
        $this->unit_price = $price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }


    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }
}
