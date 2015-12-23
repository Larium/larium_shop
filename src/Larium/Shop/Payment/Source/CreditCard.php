<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Payment\Source;

use Larium\Shop\Payment\PaymentSourceInterface;

/**
 * CreditCard
 *
 * @uses PaymentSourceInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
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
