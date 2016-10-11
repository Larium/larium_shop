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

class OptionValue
{
    /**
     * @var Larium\Shop\Store\OptionType
     */
    protected $option_type;

    /**
     * The unique name.
     *
     * @var string
     */
    protected $name;

    /**
     * The presentation title.
     *
     * @var string
     */
    protected $title;

    /**
     * @var integer
     */
    protected $position;

    public function __construct()
    {
        $this->initialize();
    }

    public function initialize()
    {
        $this->variants = new Collection(
            array(),
            'Larium\\Shop\\Store\\Variant'
        );
    }

    /**
     * Gets option type instance.
     *
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
     * @return void
     */
    public function setOptionType(OptionType $option_type)
    {
        $this->option_type = $option_type;
    }

    public function detachOptionType()
    {
        $this->option_type = null;
    }

    /**
     * Gets name.
     *
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
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets title.
     *
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
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets position.
     *
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
     * @return void
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
