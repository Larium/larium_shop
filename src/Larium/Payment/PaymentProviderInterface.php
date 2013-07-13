<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

interface PaymentProviderInterface
{
    public function authorize($amount, PaymentSourceInterface $source, array $options=array());
    
    public function purchase($amount, PaymentSourceInterface $source, array $options=array());

    public function capture($amount, $authorization, array $options=array());
}
