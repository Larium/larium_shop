<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Repository;

use Larium\FixtureHelper;

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    use FixtureHelper;

    public function __construct()
    {
        $this->fixturesSetUp();
    }

    public function getOneByNumber($number)
    {
        $orders = array_filter($this->orders(), function ($o) use ($number) {
            return $o->getNumber() == $number;
        });

        return reset($orders);
    }
}
