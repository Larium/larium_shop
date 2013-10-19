<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

/**
 * PaymentProviderInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface PaymentProviderInterface
{
    public function purchase($amount, array $options=array());

    public function setPaymentSource(PaymentSourceInterface $payment_source);
}
