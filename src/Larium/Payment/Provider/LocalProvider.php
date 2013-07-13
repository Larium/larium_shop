<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Provider;

use Larium\Payment\PaymentProviderInterface;
use Larium\Payment\PaymentSourceInterface;

class LocalProvider implements PaymentProviderInterface
{

    public function authorize($amount, PaymentSourceInterface $source, array $options=array())
    {
    
    }
    
    public function purchase($amount, PaymentSourceInterface $source, array $options=array())
    {
        return true;
    }

    public function capture($amount, $authorization, array $options=array())
    {
    
    }
}
