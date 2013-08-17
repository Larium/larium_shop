<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment\Provider;

/**
 * RedirectResponse class.
 *
 * @uses    Response
 * @package Larium\Payment
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class RedirectResponse extends Response
{
    protected $redirect_url;

    /**
     * Gets the url to redirect.
     *
     * @access public
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    public function setRedirectUrl($redirect_url)
    {
        $this->redirect_url = $redirect_url;
    }

}