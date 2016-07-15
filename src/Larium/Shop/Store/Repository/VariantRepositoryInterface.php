<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store\Repository;

interface VariantRepositoryInterface
{
    public function getOneBySku($sku);
}
