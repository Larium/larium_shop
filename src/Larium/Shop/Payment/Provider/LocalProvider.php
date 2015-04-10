<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreaskollaros@ymail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Payment\Provider;

use Larium\Shop\Payment\PaymentProviderInterface;
use Larium\Shop\Payment\PaymentSourceInterface;

/**
 * Provides an implemantation for payment methods that don't have any remote
 * call, such as cash payment, giftcards etc.
 *
 * @uses PaymentProviderInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 */
class LocalProvider implements PaymentProviderInterface
{
    protected $payment_source;

    public function purchase($amount, array $options=array())
    {
        $response = new Response();
        $response->setSuccess(true);

        return $response;
    }

    public function setPaymentSource(PaymentSourceInterface $payment_source)
    {
        $this->payment_source = $payment_source;
    }
}
