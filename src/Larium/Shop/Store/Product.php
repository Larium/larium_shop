<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

use Larium\Shop\Common\Collection;

/**
 * Product
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Product
{
    /**
     * The title of product.
     *
     * @var string
     * @access protected
     */
    protected $title;

    /**
     * The description of product.
     *
     * @var string
     * @access protected
     */
    protected $description;

    protected $permalink;

    protected $meta_description;

    protected $meta_keywords;

    protected $available_on;

    protected $created_at;

    protected $updated_at;

    protected $deleted_at;

    /**
     * option_types
     *
     * @var array|Traversable
     * @access protected
     */
    protected $option_types;

    /**
     * variants
     *
     * @var array|iterator
     * @access protected
     */
    protected $variants;

    /**
     * default_variant
     *
     * @var Larium\Shop\Store\Variant
     * @access protected
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
     * @access public
     * @return void
     */
    public function getUnitPrice()
    {
        return $this->getDefaultVariant()->getUnitPrice();
    }

    /**
     * Delegate set unit price to default variant.
     *
     * @param float|integer $price
     * @access public
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
     * @access public
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
    }

    public function addVariant(Variant $variant, $is_default=false)
    {
        $variant->setDefault();
        $this->variants->add($variant);
    }

    public function removeVariant(Variant $variant)
    {
        return $this->variants->remove($variant, function($v) use ($variant){
            return $variant->getSku() === $v->getSku();
        });
    }


    public function getDefaultVariant()
    {
        if (null === $this->default_variant)  {
            $variants = $this->getVariants();
            foreach( $variants as $v) {
                if ($v->isDefault()) {
                    $this->default_variant = $v;
                    break;
                }
            }
        }

        return $this->default_variant;
    }

    /**
     * Gets option_types.
     *
     * @access public
     * @return mixed
     */
    public function getOptionTypes()
    {
        return $this->option_types;
    }

    /**
     * Sets option_types.
     *
     * @param mixed $option_types the value to set.
     * @access public
     * @return void
     */
    public function setOptionTypes($option_types)
    {
        $this->option_types = $option_types;
    }

    public function addOptionType(OptionType $option_type)
    {
        $this->option_types->add($option_type);
    }
}
