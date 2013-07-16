<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

interface TransactionInterface
{
    /**
     * Gets the Payment object for this transaction.
     *
     * @access public
     * @return PaymentInerface
     */
    public function getPayment();

    /**
     * Sets the Payment object for this transaction.
     *
     * @param PaymentInterface $payment
     * @access public
     * @return void
     */
    public function setPayment(PaymentInterface $payment);

    public function getAmount();

    public function setAmount($amount);

    public function getTransactionId();

    public function setTransactionId($id);
}
