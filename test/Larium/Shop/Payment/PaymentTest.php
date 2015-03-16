<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

use Larium\Helper;

class PaymentTest extends \PHPUnit_Framework_TestCase
{

    use Helper;

    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testCreateInstance()
    {
        $payment = new Payment();

        $this->assertInstanceOf('Larium\\Shop\\Payment\\PaymentInterface', $payment);

        $this->assertNotNull($payment->getIdentifier());

        $this->assertInstanceOf('Larium\\Shop\\Common\\Collection', $payment->getTransactions());
    }

    public function testPayment()
    {
        $method = $this->getPaymentMethod('creditcard_payment_method');

        $payment = new Payment();

        $payment->setPaymentMethod($method);
    }
}
