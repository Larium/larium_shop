<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

interface PaymentResourceInterface
{
    public function getBalance();

    public function getNumber();

    public function isExpired();

    public function getCost();

    public function getDescription();
}
