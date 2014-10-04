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
    /**
     * first_name
     *
     * @var string
     * @access protected
     */
    protected $first_name;

    /**
     * last_name
     *
     * @var string
     * @access protected
     */
    protected $last_name;

    /**
     * address1
     *
     * @var string
     * @access protected
     */
    protected $address1;

    /**
     * address2
     *
     * @var string
     * @access protected
     */
    protected $address2;

    /**
     * city
     *
     * @var string
     * @access protected
     */
    protected $city;

    /**
     * state
     *
     * @var string
     * @access protected
     */
    protected $state;

    /**
     * country
     *
     * @var string
     * @access protected
     */
    protected $country;

    /**
     * zip
     *
     * @var string
     * @access protected
     */
    protected $zip;

    /**
     * phone
     *
     * @var string
     * @access protected
     */
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
     * @param string $address1 the value to set.
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
     * @param string $address2 the value to set.
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
     * @param string $city the value to set.
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
     * @param string $state the value to set.
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
     * @param string $country the value to set.
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
     * @param string $zip the value to set.
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
     * @param string $phone the value to set.
     *
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}
