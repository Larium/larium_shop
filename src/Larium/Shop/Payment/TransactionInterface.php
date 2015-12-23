<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

/**
 * TransactionInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface TransactionInterface
{
    /**
     * Gets the Payment object for this transaction.
     *
     * @return PaymentInerface
     */
    public function getPayment();

    /**
     * Sets the Payment object for this transaction.
     *
     * @param PaymentInterface $payment
     * @return void
     */
    public function setPayment(PaymentInterface $payment);

    /**
     * Gets the amount of transaction
     *
     * @return float|integer
     */
    public function getAmount();

    /**
     * Sets the amount of transaction
     *
     * @param float|integer $amount
     * @return void
     */
    public function setAmount($amount);

    /**
     * Returns a uniq id that represents this transaction.
     *
     * @return string
     */
    public function getTransactionId();

    /**
     * Sets TransactionId
     *
     * @param string $id
     * @return void
     */
    public function setTransactionId($id);
}
