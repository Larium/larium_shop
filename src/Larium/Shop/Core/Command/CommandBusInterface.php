<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Core\Command;

interface CommandBusInterface
{
    public function handle($command);
}
