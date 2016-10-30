<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Core\Command;

interface CommandHandlerInterface
{
    /**
     * @return Larium\Shop\Core\Event\EventProviderInterface
     */
    public function getEventProvider();
}
