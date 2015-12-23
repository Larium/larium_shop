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
 * PaymentSourceInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
 */
interface PaymentSourceInterface
{
    /**
     * Get the amount balance of this resource
     *
     * @return integer|float
     */
    public function getBalance();

    /**
     * Gets an identification number for this resource
     *
     * @return void
     */
    public function getNumber();

    /**
     * Checks if resource has expired and cannot be used.
     *
     * @return boolean
     */
    public function isExpired();

    public function setOptions(array $options = array());

    public function getOptions();
}
