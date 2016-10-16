<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

interface StoreInterface
{
    public function getName();

    /**
     * Get supported currency for this store.
     *
     * @return Money\Currency
     */
    public function getCurrency();

    public function getCountry();
}
