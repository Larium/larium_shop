<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shipment;

interface AddressInterface
{
    public function getName();

    public function getAddress1();

    public function getAddress2();

    public function getCity();

    public function getState();

    public function getCountry();

    public function getZip();

    public function getPhone();
}
