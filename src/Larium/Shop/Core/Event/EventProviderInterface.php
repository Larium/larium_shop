<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Core\Event;

interface EventProviderInterface
{
    public function releaseEvents();

    public function raise(EventInterface $event);
}
