<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\StateMachine;

use Finite\Transition\Transition as FiniteTransition;
use Finite\StateMachine\StateMachine as FiniteStateMachine;

/**
 * Transition
 *
 * @uses FiniteTransition
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class Transition extends FiniteTransition
{

    protected $callback;

    protected $condition;

    public function __construct(
        $name,
        $initialStates,
        $state,
        $callback = null,
        $condition = null
    ) {
        parent::__construct($name, $initialStates, $state);

        $this->callback = $callback;
        $this->condition = $condition;
    }

    public function process(FiniteStateMachine $stateMachine)
    {
        if (true === $this->call_condition($stateMachine)) {
            return $this->invoke_callback($stateMachine);
        }
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

    private function call_condition($stateMachine)
    {
        $callable = $this->condition;

        if (is_array($callable) && 2 == count($callable)) {

            $method = new \ReflectionMethod($callable[0], $callable[1]);

            return $method->invokeArgs($callable[0], array($stateMachine, $this));

        } elseif (is_callable($callable)) {

            $function = new \ReflectionFunction($callable);

            return $function->invokeArgs(array($stateMachine, $this));
        }

        return true;
    }
}
