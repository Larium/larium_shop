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
namespace Larium\Shop\Payment;

/**
 * PaymentProviderInterface
 *
 * A payment provider can actual change the state of a payment.
 * It can execute actions that could charge or refund money on remote systems
 * like Payment Gateways or local events like cash payment etc.
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 */
interface PaymentProviderInterface
{
    public function purchase($amount, array $options = array());

    public function setPaymentSource(PaymentSourceInterface $payment_source);
}
