<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

interface PaymentMethodInterface
{
    public function getTitle();

    public function getCost();

    public function getSourceClass();

    public function setSourceClass($source_class);

    public function getProviderClass();

    public function setProviderClass($provider_class);

    public function getPaymentSource();

    public function getProvider();

    public function getSourceOptions();

    public function setSourceOptions(array $options=array());
}
