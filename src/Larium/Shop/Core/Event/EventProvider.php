<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Core\Event;

class EventProvider implements EventProviderInterface
{
    private $events = [];

    public function releaseEvents()
    {
        $events = $this->events;
        $this->events = array();

        return $events;
    }

    public function raise(EventInterface $event)
    {
        $this->events[] = $event;
    }
}
