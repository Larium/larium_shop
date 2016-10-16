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
namespace Larium\Shop\Addressing;

/**
 * AddressInterface
 *
 * @author  Andreas Kollaros <andreas@larium.net>
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
