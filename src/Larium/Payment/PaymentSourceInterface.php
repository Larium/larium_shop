<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

interface PaymentSourceInterface
{
    public function getBalance();

    public function getNumber();

    public function isExpired();

    public function setOptions(array $options=array());

    public function getOptions();
}
