<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment\Provider;

class Response
{
    protected $success;

    protected $message;

    protected $transaction_id;

    public function isSuccess()
    {
        return $this->success;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * Gets transaction_id.
     *
     * @access public
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Sets transaction_id.
     *
     * @param mixed $transaction_id the value to set.
     * @access public
     * @return void
     */
    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }
}
