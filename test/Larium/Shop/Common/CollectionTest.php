<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Common;

class CollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection();
    }

    public function testCollectionAdd()
    {
        $this->assertFalse(isset($this->collection[0]));

        $this->collection[] = 'alpha';

        $this->assertFalse($this->collection->count() == 0);

        $this->collection[] = 'beta';

        $this->assertEquals('alpha', $this->collection->first());
        $this->assertEquals('beta', $this->collection->last());
    }

    public function testRemoveObjectElement()
    {
        $a = new \stdclass;
        $b = new \stdclass;
        $c = new \stdclass;
        $d = new \stdclass;
        $e = new \stdclass;

        $a->name = 'alpha';
        $b->name = 'beta';
        $c->name = 'gamma';
        $d->name = 'gamma';
        $e->name = 'epsilon';

        $this->collection->add($a);
        $this->collection->add($b);
        $this->collection->add($c);

        $removed = $this->collection->remove($d, function($var) use ($d){
            return $var->name == $d->name;
        });

        $this->assertTrue($removed);

        $removed = $this->collection->remove($b);

        $this->assertTrue($removed);

        $removed = $this->collection->remove($d, function($var) use ($e){
            return $var->name == $e->name;
        });

        $this->assertFalse($removed);

        $this->assertTrue($this->collection->count() == 1);

    }

    public function testContainsObjectElement()
    {
        $a = new \stdclass;
        $b = new \stdclass;
        $c = new \stdclass;
        $d = new \stdclass;
        $e = new \stdclass;

        $a->name = 'alpha';
        $b->name = 'beta';
        $c->name = 'gamma';
        $d->name = 'alpha';
        $e->name = 'epsilon';

        $this->collection->add($a);
        $this->collection->add($b);
        $this->collection->add($c);

        $this->assertTrue($this->collection->contains($a));

        $this->assertTrue(
            false !== $this->collection->contains($d, function($var) use($d) {
                return $var->name == $d->name;
            })
        );

        $this->assertFalse($this->collection->contains($d));
    }
}
