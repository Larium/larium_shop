<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

/**
 * PaymentSourceInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface PaymentSourceInterface
{
    /**
     * Get the amount balance of this resource
     *
     * @access public
     * @return integer|float
     */
    public function getBalance();

    /**
     * Gets an identification number for this resource
     *
     * @access public
     * @return void
     */
    public function getNumber();

    /**
     * Checks if resource has expired and cannot be used.
     *
     * @access public
     * @return boolean
     */
    public function isExpired();

    public function setOptions(array $options = array());

    public function getOptions();
}
