<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Core\Command;

use Larium\Shop\Core\Event\EventProviderInterface;

abstract class CommandHandler implements CommandHandlerInterface
{
    private $eventProvider;

    public function setEventProvider(EventProviderInterface $eventProvider)
    {
        $this->eventProvider = $eventProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventProvider()
    {
        return $this->eventProvider;
    }
}
