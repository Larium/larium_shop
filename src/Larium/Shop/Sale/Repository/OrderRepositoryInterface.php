<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Repository;

interface OrderRepositoryInterface
{
    public function getOneByNumber($number);
}
