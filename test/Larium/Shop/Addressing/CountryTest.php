<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Addressing;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $country = new Country(
            'Greece',
            null,
            'GRE'
        );

        print_r($country);
    }
}
