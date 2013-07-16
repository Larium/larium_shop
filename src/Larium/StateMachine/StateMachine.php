<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\StateMachine;

use Finite\StateMachine\StateMachine as FiniteStateMachine;
use Finite\State\State;

class StateMachine extends FiniteStateMachine
{

    public function nextTransition()
    {
        $state = $this->getCurrentState();
        if (State::TYPE_FINAL === $state->getType()) {
            throw new \LogicException('Can not proceed the transition from a final state');
        }

        $next = null;

        foreach ($this->transitions as $name => $transition) {

            if (in_array($state->getName(), $transition->getInitialStates())) {
                $next = $name;
                break;
            }
        }

        if (null === $next) {
            throw new \OutOfRangeException('Could not find next Transition');
        }

        return $this->apply($next);
    }
}
