<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment\Provider;

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreaskollaros@ymail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class GatewayResponse extends Response
{
    protected $transaction_id;

    /**
     * Gets the transaction id of a success payment action.
     *
     * @access public
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Sets the transaction id of a success payment action.
     *
     * @param string $transaction_id The value returned from remote system
     *                               whiche indicates a success action.
     * @access public
     * @return void
     */
    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }
}
