<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Shipment;

/**
 * Address
 *
 * @uses AddressInterface
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Address implements AddressInterface
{
    protected $first_name;

    protected $last_name;

    protected $address1;

    protected $address2;

    protected $city;

    protected $state;

    protected $country;

    protected $zip;

    protected $phone;

    /**
     * {@inheritdoc }
     */
    public function getName()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    public function getFistName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * {@inheritdoc }
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Sets address1.
     *
     * @param mixed $address1 the value to set.
     *
     * @return void
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    /**
     * {@inheritdoc }
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Sets address2.
     *
     * @param mixed $address2 the value to set.
     *
     * @return void
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * {@inheritdoc }
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets city.
     *
     * @param mixed $city the value to set.
     *
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * {@inheritdoc }
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets state.
     *
     * @param mixed $state the value to set.
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * {@inheritdoc }
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets country.
     *
     * @param mixed $country the value to set.
     *
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * {@inheritdoc }
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets zip.
     *
     * @param mixed $zip the value to set.
     *
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * {@inheritdoc }
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets phone.
     *
     * @param mixed $phone the value to set.
     *
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}
