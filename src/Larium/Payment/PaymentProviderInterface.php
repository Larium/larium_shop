<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

interface PaymentProviderInterface
{
    public function purchase($amount, array $options=array());

    public function setPaymentSource(PaymentSourceInterface $payment_source);
}
