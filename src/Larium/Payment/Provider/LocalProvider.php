<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Provider;

use Larium\Payment\PaymentProviderInterface;
use Larium\Payment\PaymentSourceInterface;

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
