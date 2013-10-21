<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

class OptionValue
{
    /**
     * option_type
     *
     * @var Larium\Shop\Store\OptionType
     * @access protected
     */
    protected $option_type;

    protected $name;

    protected $title;

    /**
     * position
     *
     * @var integer
     * @access protected
     */
    protected $position;

    /**
     * Gets option_type.
     *
     * @access public
     * @return Larium\Shop\Store\OptionType
     */
    public function getOptionType()
    {
        return $this->option_type;
    }

    /**
     * Sets option_type.
     *
     * @param Larium\Shop\Store\OptionType $option_type the value to set.
     * @access public
     * @return void
     */
    public function setOptionType(OptionType $option_type)
    {
        $this->option_type = $option_type;
    }

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

    /**
     * Gets position.
     *
     * @access public
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets position.
     *
     * @param mixed $position the value to set.
     * @access public
     * @return void
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
