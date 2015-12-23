<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Payment;

use Larium\Shop\Sale\OrderInterface;

/**
 * PaymentInterface allows the payoff of an Order.
 *
 * Each Payment object must have a PaymentSource which indicate the medium
 * from where the Order will be fullfilled.
 *
 * Payment should use PaymentSource to create one or more transactions for a
 * given amount. The amount to send to a Transaction can be the total amount that
 * fullfill the Order or a part of it.
 * Also the amount of Payment could be part or all of the amount of Order.
 * Order can use multiple Payment objects to fullfill the required amount.
 *
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface PaymentInterface
{
    /**
     * Gets all transactions associated with this payment.
     *
     * @return array|Traversable
     */
    public function getTransactions();

    /**
     * Adds a new Transaction to Payment
     *
     * @param TransactionInterface $transaction
     * @return void
     */
    public function addTransaction(TransactionInterface $transaction);

    /**
     * Removes a Transaction from Payment
     *
     * @param TransactionInterface $transaction
     * @return void
     */
    public function removeTransaction(TransactionInterface $transaction);

    /**
     * Checks if given Transaction object exists.
     *
     * @param TransactionInterface $transaction
     * @return void
     */
    public function containsTransaction(TransactionInterface $transaction);

    /**
     * Sets the amount of money to pay.
     *
     * @param Money\Money $amount
     * @return void
     */
    public function setAmount($amount);

    /**
     * Gets the amount to pay.
     *
     * @return Money\Money
     */
    public function getAmount();

    /**
     * Gets the current state of Payment.
     *
     * @return string
     */
    public function getState();

    /**
     * Sets the current state for Payment.
     *
     * @param string $state
     *
     * @return void
     */
    public function setState($state);

    /**
     * Gets the PaymentMethod for this Payment.
     *
     * @return PaymentMethod
     */
    public function getPaymentMethod();

    /**
     * Sets the PaymentMethod for this Payment.
     *
     * @return void
     */
    public function setPaymentMethod(PaymentMethod $payment_method);

    /**
     * Gets the unique identifier for this payment.
     *
     * @return string
     */
    public function getIdentifier();


    /**
     * Gets the Order object associated with this Payment.
     *
     * @return Larium\Shop\Sale\OrderInterface
     */
    public function getOrder();

    /**
     * Sets associated Order object.
     *
     * @param Larium\Shop\Sale\OrderInterface $order
     * @return void
     */
    public function setOrder(OrderInterface $order);

    /**
     * Detach the Order object from Payment.
     *
     * Additional remove any adjustment created by Payment to Order.
     *
     * @return void
     */
    public function detachOrder();

    /**
     * Tries to process payment to the given state.

     * @throws InvalidArgumentException If PaymentMethod objects is missing.
     * @param string $action
     * @return false|Larium\Shop\Payment\Provider\Response
     */
    public function process($state = null);
}
