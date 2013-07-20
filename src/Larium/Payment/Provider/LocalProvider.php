<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Provider;

use Larium\Payment\PaymentProviderInterface;
use Larium\Payment\PaymentSourceInterface;

class LocalProvider implements PaymentProviderInterface
{
    public function purchase($amount, PaymentSourceInterface $source=null, array $options=array())
    {
        $response = new Response();
        $response->setSuccess(true);

        return $response;
    }
}
