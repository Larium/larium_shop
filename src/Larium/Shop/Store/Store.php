<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

class Store
{
    private $name;

    private $currency;

    private $country;

    public function __construct($name, $currency, $country)
    {
        $this->name = $name;
        $this->currency = $currency;
        $this->country = $country;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
