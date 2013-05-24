<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Payment;

class BillingMethod
{
    protected $id;

    protected $title;

    protected $description;

    protected $cost;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get title.
     *
     * @return string.
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set title.
     *
     * @param string $title the value to set.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set description.
     *
     * @param string $description the value to set.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * Get cost.
     *
     * @return number
     */
    public function getCost()
    {
        return $this->cost;
    }
    
    /**
     * Set cost.
     *
     * @param number $cost the value to set.
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }
}
