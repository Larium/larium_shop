<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Resource;

use Larium\Payment\PaymentResourceInterface;

class CreditCard implements PaymentResourceInterface
{
    protected $number;

    protected $description = "Credit Card";

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

    public function getCost()
    {
        return 0;
    }

    public function getDescription()
    {
        return $this->description;
    }
}

