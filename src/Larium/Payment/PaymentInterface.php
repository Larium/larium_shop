<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

use Larium\Sale\OrderInterface;

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
 * @package Larium\Payment 
 * @author  Andreas Kollaros <andreaskollaros@ymail.com> 
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface PaymentInterface
{
    /**
     * getTransactions 
     * 
     * @access public
     * @return array|Traversable
     */
    public function getTransactions();

    /**
     * Adds a new Transaction to Payment
     * 
     * @param TransactionInterface $transaction 
     *
     * @access public
     * @return void
     */
    public function addTransaction(TransactionInterface $transaction);

    /**
     * Removes a Transaction from Payment
     * 
     * @param TransactionInterface $transaction 
     *
     * @access public
     * @return void
     */
    public function removeTransaction(TransactionInterface $transaction);
    
    public function containsTransaction(TransactionInterface $transaction);

    public function getTotalTransactionsAmount();

    public function setAmount($amount);

    public function getAmount();


    /**
     * Gets teh current state o Payment. 
     * 
     * @access public
     * @return string
     */
    public function getState();

    /**
     * Sets the current state for Payment.
     * 
     * @param string $state
     *
     * @access public
     * @return void
     */
    public function setState($state);

    /**
     * Gets the PaymentMethod for this Payment. 
     * 
     * @access public
     * @return PaymentMethod
     */
    public function getPaymentMethod();

    public function getIdentifier();
    
    /**
     * Delegate
     *
     * Gets the cost of this payment according to PaymentSource
     * 
     * @access public
     * @return number
     */
    public function getCost();
    
    /**
     * Delegate
     *
     * Gets the descriptions of this payment according to PaymentSource
     * 
     * @access public
     * @return string
     */
    public function getDescription();

    public function getOrder();

    public function setOrder(OrderInterface $order);

    public function detachOrder();

    public function process();
}
