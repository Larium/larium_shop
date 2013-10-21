<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

class OptionType
{
    /**
     * The identification name.
     *
     * @var string A lowercase string with only letters.
     * @access protected
     */
    protected $name;

    /**
     * The presentation title.
     *
     * @var string
     * @access protected
     */
    protected $title;

    /**
     * Gets name.
     *
     * @access public
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     *
     * @param mixed $name the value to set.
     * @access public
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets title.
     *
     * @access public
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets title.
     *
     * @param mixed $title the value to set.
     * @access public
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
