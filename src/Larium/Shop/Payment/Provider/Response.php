<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment\Provider;

class Response
{
    /**
     * Indicates the status of successful response.
     *
     * @var boolean
     * @access protected
     */
    protected $success;

    /**
     * The message from response
     *
     * @var string
     * @access protected
     */
    protected $message;

    /**
     * isSuccess
     *
     * @access public
     * @return void
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Gets the message returned from remote gateway.
     *
     * @access public
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the message that returned from remote gateway.
     *
     * @param string $message
     * @access public
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Set the success status of a response.
     *
     * @param boolean $success
     * @access public
     * @return void
     */
    public function setSuccess($success)
    {
        $this->success = (boolean) $success;
    }
}
