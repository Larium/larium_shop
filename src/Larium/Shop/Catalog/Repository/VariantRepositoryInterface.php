<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Catalog\Repository;

interface VariantRepositoryInterface
{
    /**
     * @param string $sku The SKU to lookup
     * @return Larium\Shop\Catalog\Variant|null
     */
    public function getOneBySku($sku);
}
