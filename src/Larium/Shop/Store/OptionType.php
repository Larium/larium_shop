<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

use Larium\Shop\Common\Collection;

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
     * option_values
     *
     * @var Larium\Shop\Common\Collection
     * @access protected
     */
    protected $option_values;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->option_values = new Collection(
            array(),
            'Larium\\Shop\\Store\\OptionValue'
        );
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
     * Sets option_values.
     *
     * @param CollectionInterface $option_values the value to set.
     * @access public
     * @return void
     */
    public function setOptionValues(CollectionInterface $option_values)
    {
        $this->option_values = $option_values;
    }

    public function addOptionValue(OptionValue $option_value)
    {
        $this->option_values->append($option_value);
        $option_value->setOptionType($this);
    }

    /**
     * Removes an OptionValue element from Collection.
     *
     * @param OptionValue $option_value
     * @access public
     * @return void
     */
    public function removeOptionValue(OptionValue $option_value)
    {
        $option_value->detachOptionType();
        return $this->option_values->remove(
            $option_value,
            function ($var) use ($option_value) {
                return $var->getName() == $option_value->getName();
            }
        );
    }
}
