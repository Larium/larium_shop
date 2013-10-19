<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

/**
 * TransactionInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
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

    /**
     * Gets the amount pf transaction
     *
     * @access public
     * @return float|integer
     */
    public function getAmount();

    /**
     * Sets the amount of transaction
     *
     * @param float|integer $amount
     * @access public
     * @return void
     */
    public function setAmount($amount);

    public function getTransactionId();

    public function setTransactionId($id);
}
