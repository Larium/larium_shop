<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Store;

class Product
{
    protected $title;

    protected $description;

    protected $permalink;

    protected $meta_description;

    protected $meta_keywords;

    protected $available_on;

    protected $created_at;

    protected $updated_at;

    protected $deleted_at;

    protected $variants;

    protected $default_variant;

    public function __construct()
    {
        $this->variants = new \SplObjectStorage();

        $default = new Variant();
        $this->addVariant($default, true);
    }

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

    public function getSku()
    {
        return $this->getDefaultVariant()->getSku();
    }

    public function getVariants()
    {
        return $this->variants;
    }

    public function setVariants($variants)
    {
        $this->variants = $variants;
    }

    public function addVariant(Variant $variant, $is_default=false)
    {
        $variant->setIsDefault($is_default);
        $this->variants->attach($variant);
    }

    public function removeVariant(Variant $variant)
    {
        $this->variants->detach($variant);
    }


    public function getDefaultVariant()
    {
        if (null === $this->default_variant)  {
            $variants = $this->getVariants();
            foreach( $variants as $v) {
                if ($v->getIsDefault()) {
                    $this->default_variant = $v;
                    break;
                }
            }
        }

        return $this->default_variant;
    }
}
