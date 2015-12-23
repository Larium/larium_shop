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
 * CashOnDelivery
 *
 * @uses PaymentSourceInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class CashOnDelivery implements PaymentSourceInterface
{
    public function setOptions(array $options = array())
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
