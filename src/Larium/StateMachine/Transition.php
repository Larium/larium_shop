<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\StateMachine;

use Finite\Transition\Transition as FiniteTransition;
use Finite\StateMachine\StateMachine as FiniteStateMachine;

class Transition extends FiniteTransition
{

    protected $callback;

    public function __construct($name, $initialStates, $state, $callback=null)
    {
        parent::__construct($name, $initialStates, $state);

        $this->callback = $callback;
    }

    public function process(FiniteStateMachine $stateMachine)
    {
        return $this->invoke_callback($stateMachine);
    }

    protected function invoke_callback($stateMachine)
    {
        $callable = $this->callback;
        
        if (is_array($callable) && 2 == count($callable)) {
            
            $method = new \ReflectionMethod($callable[0], $callable[1]);

            return $method->invokeArgs($callable[0], array($stateMachine, $this));

        } elseif (is_callable($callable)) {
            
            $function = new \ReflectionFunction($callable);

            return $function->invokeArgs(array($stateMachine, $this));
        }
    }
}
