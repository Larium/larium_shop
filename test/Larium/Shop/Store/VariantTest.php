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

        $option_type = $this->getOptionType('size_option_type');
        $small = new OptionValue();
        $small->setName('small');
        $small->setTitle('small');
        $small->setOptionType($option_type);

        $option_values = $this->populateOptionValues();

        $variant->setOptionValues(new Collection($option_values));

        $this->assertEquals(
            count($option_values),
            $variant->getOptionValues()->count()
        );

        $variant->removeOptionValue($small);

        $this->assertEquals(
            count($option_values) - 1,
            $variant->getOptionValues()->count()
        );

        //$variant->getOptionValues()->append(new \stdclass);
        //$variant->getOptionValues()[]=new \stdclass;
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
