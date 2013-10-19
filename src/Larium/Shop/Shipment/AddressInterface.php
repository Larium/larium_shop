<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

/**
 * AddressInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface AddressInterface
{
    public function getName();

    /**
     * Gets 1st line address.
     *
     * @return string
     */
    public function getAddress1();

    /**
     * Gets 2nd line address.
     *
     * @return string
     */
    public function getAddress2();

    /**
     * Gets city.
     *
     * @return string
     */
    public function getCity();

    /**
     * Gets state.
     *
     * @return string
     */
    public function getState();

    /**
     * Gets country.
     *
     * @return string
     */
    public function getCountry();

    /**
     * Gets zip.
     *
     * @return string
     */
    public function getZip();

    /**
     * Gets phone number.
     *
     * @return string
     */
    public function getPhone();
}
