<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Source;

use Larium\Payment\PaymentSourceInterface;

class CashOnDelivery implements PaymentSourceInterface
{
    public function setOptions(array $options=array())
    {
    }

    public function getBalance()
    {
        return 0;
    }

    public function getNumber()
    {
        return uniqid();
    }

    public function isExpired()
    {
        return false;
    }

    public function getOptions()
    {
        return array();
    }
}
