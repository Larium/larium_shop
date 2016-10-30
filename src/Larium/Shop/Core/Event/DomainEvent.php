<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Core\Event;

abstract class DomainEvent implements EventInterface
{
    public function getName()
    {
        return static::NAME;
    }
}
