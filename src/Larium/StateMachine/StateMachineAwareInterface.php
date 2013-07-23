<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\StateMachine;

use Symfony\Component\EventDispatcher\EventDispatcher;

interface StateMachineAwareInterface
{
    public function getStateMachine();

    public function getStates();

    public function getTransitions();

    public function setupEvents();
}
