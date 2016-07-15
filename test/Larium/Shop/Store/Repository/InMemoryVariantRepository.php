<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store\Repository;

use Larium\FixtureHelper;
use Larium\Shop\Store\Variant;

class InMemoryVariantRepository implements VariantRepositoryInterface
{
    use FixtureHelper;

    public function __construct()
    {
        $this->fixturesSetUp();
    }

    public function getOneBySku($sku)
    {
        $variants = $this->variants();
        $variant = array_filter($variants, function ($v) use ($sku) {
            return $v instanceof Variant
                && $v->getSku() == $sku;
        });

        return reset($variant);
    }
}
