<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

/**
 * PaymentInterface allows the payoff of an Order.
 *
 * Each Payment object must have a PaymentResource which indicate the medium 
 * from where the Order will be fullfilled.
 *
 * Payment should use PaymentResource to create one or more transactions for a 
 * given amount. The amount to send to a Transaction can be the total amount that 
 * fullfill the Order or a part of it.
 * Also the amount of Payment could be part or all of the amount of Order.
 * Order can use multiple Payment object to fullfill the required amount.
 * 
 * 
 * @package Larium\Payment 
 * @author  Andreas Kollaros <andreaskollaros@ymail.com> 
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface PaymentInterface
{
    public function getTransactions();

    public function addTransaction(TransactionInterface $transaction);

    public function removeTransaction(TransactionInterface $transaction);
    
    public function containsTransaction(TransactionInterface $transaction);

    public function getTotalTransactionsAmount();

    public function setAmount($amount);

    public function getAmount();

    /**
     * Gets the payment resource for this payment.
     *
     * Payment resources are objects that satisfy the Payment amount.
     * Can be Creditcards, Giftcards, Cash, Checks etc.
     * 
     * @access public
     * @return PaymentResourceInterface
     */
    public function getResource();

    public function setResource(PaymentResourceInterface $resource);

    public function getIdentify();
    
    /**
     * Gets the cost of this payment according to PaymentResource
     * 
     * @access public
     * @return number
     */
    public function getCost();
    
    /**
     * Gets the descriptions of this payment according to PaymentResource
     * 
     * @access public
     * @return string
     */
    public function getDescription();
}
