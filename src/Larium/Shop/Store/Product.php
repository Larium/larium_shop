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
namespace Larium\Shop\Store;

use Larium\Shop\Common\Collection;

/**
 * Product
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class Product
{
    /**
     * The title of product.
     *
     * @var string
     */
    protected $title;

    /**
     * The description of product.
     *
     * @var string
     */
    protected $description;

    /**
     * permalink
     *
     * @var string
     */
    protected $permalink;

    /**
     * meta_description
     *
     * @var string
     */
    protected $meta_description;

    /**
     * meta_keywords
     *
     * @var string
     */
    protected $meta_keywords;

    /**
     * available_on
     *
     * @var DateTime
     */
    protected $available_on;

    /**
     * created_at
     *
     * @var DateTime
     */
    protected $created_at;

    /**
     * updated_at
     *
     * @var DateTime
     */
    protected $updated_at;

    /**
     * deleted_at
     *
     * @var DateTime
     */
    protected $deleted_at;

    /**
     * option_types
     *
     * @var Larium\Common\Collection
     */
    protected $option_types;

    /**
     * variants
     *
     * @var Larium\Common\Collection
     */
    protected $variants;

    /**
     * default_variant
     *
     * @var Larium\Shop\Store\Variant
     */
    protected $default_variant;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->variants = new Collection();
        $this->option_types = new Collection();

        $default = new Variant();
        $this->addVariant($default, true);
    }

    /**
     * Delegate get unit price from default variant.
     *
     * @return void
     */
    public function getUnitPrice()
    {
        return $this->getDefaultVariant()->getUnitPrice();
    }

    /**
     * Delegate set unit price to default variant.
     *
     * @param int $price
     * @return void
     */
    public function setUnitPrice($price)
    {
        $this->getDefaultVariant()->setUnitPrice($price);
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

    /**
     * Delegate get sku number from default variant.
     *
     * @return string
     */
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
        foreach ($variants as $variant) {
            $variant->setProduct($this);
        }
    }

    public function addVariant(Variant $variant, $is_default = false)
    {
        if ($is_default) {
            $variant->setDefault();
        }
        $this->variants->add($variant);
        $variant->setProduct($this);
    }

    public function removeVariant(Variant $variant)
    {
        return $this->variants->remove($variant, function ($v) use ($variant) {
            return $variant->getSku() === $v->getSku();
        });
    }

    public function getDefaultVariant()
    {
        if (null === $this->default_variant) {
            $variants = $this->getVariants();
            foreach ($variants as $v) {
                if ($v->isDefault()) {
                    $this->default_variant = $v;
                    break;
                }
            }
        }

        return $this->default_variant;
    }

    /**
     * Gets option types collection.
     *
     * @return Collection
     */
    public function getOptionTypes()
    {
        return $this->option_types;
    }

    /**
     * Sets option types collection
     *
     * @param Collection $option_types the value to set.
     * @return void
     */
    public function setOptionTypes(Collection $option_types)
    {
        $this->option_types = $option_types;
    }

    public function addOptionType(OptionType $option_type)
    {
        $this->option_types->add($option_type);
    }
}
