<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

class OptionValue
{
    /**
     * @var Larium\Shop\Store\OptionType
     * @access protected
     */
    protected $option_type;

    /**
     * name
     *
     * @var string
     * @access protected
     */
    protected $name;

    /**
     * title
     *
     * @var string
     * @access protected
     */
    protected $title;

    /**
     * @var integer
     * @access protected
     */
    protected $position;

    /**
     * Gets option type instance.
     *
     * @access public
     * @return Larium\Shop\Store\OptionType
     */
    public function getOptionType()
    {
        return $this->option_type;
    }

    /**
     * Sets option type instance.
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
     * @return string
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
     * @return string
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
     * @return integer
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
