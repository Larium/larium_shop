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

/**
 * PaymentMethodInterface
 *
 * Describes the role of a Payment Method.
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface PaymentMethodInterface
{
    /**
     * Gets the title for this PaymentMethod.
     *
     * @return string.
     */
    public function getTitle();

    /**
     * Gets the cost of using this PaymentMethod.
     * Optional.
     *
     * @return Money\Money
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
