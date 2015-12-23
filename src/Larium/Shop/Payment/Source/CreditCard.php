<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment\Source;

use Larium\Shop\Payment\PaymentSourceInterface;

class CreditCard implements PaymentSourceInterface
{
    protected $number;

    protected $options;

    public function setOptions(array $options = array())
    {
        $this->options = $options;

        $this->number = isset($options['number'])
            ? $options['number']
            : null;
    }

    public function getOptions()
    {
        return $this->options;
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
}
