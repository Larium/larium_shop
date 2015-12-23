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
namespace Larium\Shop\Payment\Provider;

/**
 * RedirectResponse class.
 *
 * @uses    Response
 * @package Larium\Shop\Payment
 * @author  Andreas Kollaros <andreas@larium.net>
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

    /**
     * Sets the url to redirect
     *
     * @param string $redirect_url
     *
     * @access public
     * @return void
     */
    public function setRedirectUrl($redirect_url)
    {
        $this->redirect_url = $redirect_url;
    }
}
