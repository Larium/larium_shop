<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Source;

use Larium\Payment\PaymentSourceInterface;

class CreditCard implements PaymentSourceInterface
{
    protected $number;

    protected $description = "Credit Card";

    public function setOptions(array $options=array())
    {
        $this->number = isset($options['number'])
            ? $options['number']
            : null;
    }

    public function getBalance()
    {
        return 0;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function isExpired()
    {
        return false;
    }

    public function getDescription()
    {
        return $this->description;
    }
}

