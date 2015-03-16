<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

/**
 * PaymentMethodInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface PaymentMethodInterface
{
    /**
     * Gets the describe title for this PaymentMethod.
     *
     * @return string.
     */
    public function getTitle();

    /**
     * Gets the cost of using this PaymentMethod if any.
     *
     * @return number
     */
    public function getCost();

    /**
     * Gets the PaymentSource for this Payment.
     *
     * Payment sources are objects that satisfy the Payment amount.
     * Can be Creditcards, Giftcards, Cash, Checks etc.
     *
     * @access public
     * @return PaymentSourceInterface
     */
    public function getPaymentSource();

    /**
     * Gets the provider to process the Payment.
     *
     * @access public
     * @return PaymentProviderInterface
     */
    public function getProvider();

    /**
     * Gets source options.
     *
     * @access public
     * @return array
     */
    public function getSourceOptions();

    /**
     * Sets options for PaymentSource.
     *
     * @param array $options
     * @access public
     * @return void
     */
    public function setSourceOptions(array $options = array());

    /**
     * Sets the payment action to perform on Payment.
     * Available options are:
     * - purchase
     * - authorize
     * - capture
     * - credit
     * - void
     *
     * @param string $action
     * @access public
     * @return void
     */
    public function setAction($action);

    /**
     * Gets the payment action.
     *
     * @access public
     * @return string
     */
    public function getAction();
}
