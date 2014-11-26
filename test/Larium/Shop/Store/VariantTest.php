<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Store;

use Larium\Shop\Common\Collection;

class VariantTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->loader = new \FixtureLoader();
    }

    public function testCreateVariantOptionValues()
    {
        $variant = new Variant();

        $options_values = $this->populateOptionValues();
        foreach ($options_values as $option_value) {
            $variant->addOptionValue($option_value);
        }

        $this->assertEquals(
            count($options_values),
            $variant->getOptionValues()->count()
        );

        $small = new OptionValue();
        $small->setName('small');
        $variant->addOptionValue($small);

        $this->assertEquals(
            count($options_values),
            $variant->getOptionValues()->count()
        );

        if ($variant->removeOptionValue($small)) {
            $this->assertEquals(
                count($options_values) - 1,
                $variant->getOptionValues()->count()
            );
        }
    }

    private function getOptionType($id)
    {
        $data = $this->loader->getData();
        $hydrator = new \Hydrator('Larium\Shop\\Store\\OptionType');

        return $hydrator->hydrate($data[$id], $id);

    }

    private function populateOptionValues()
    {
        $output = array();

        $fixtures = array(
            array('Small', 'small'),
            array('Medium', 'medium'),
            array('Large', 'large'),
            array('XLarge', 'xlarge'),
            array('XXLarge', 'xxlarge')
        );

        $option_type = $this->getOptionType('size_option_type');

        foreach ($fixtures as $index => $f) {
            $option_value = new OptionValue();
            $option_value->setOptionType($option_type);
            $option_value->setTitle($f[0]);
            $option_value->setName($f[1]);
            $option_value->setPosition($index);
            $output[] = $option_value;
        }

        return $output;
    }
}
