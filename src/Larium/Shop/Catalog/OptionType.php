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
namespace Larium\Shop\Catalog;

use Larium\Shop\Common\Collection;

class OptionType
{
    /**
     * The identification name.
     *
     * @var string A lowercase string with only letters.
     */
    protected $name;

    /**
     * The presentation title.
     *
     * @var string
     */
    protected $title;

    /**
     * option_values
     *
     * @var Larium\Shop\Common\Collection
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
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets title.
     *
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
