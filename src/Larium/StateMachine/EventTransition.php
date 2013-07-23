<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\StateMachine;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Finite\Event\FiniteEvents;

/**
 * EventTransition class for setting listeners for specified Transitions.
 *
 * This class provides the functionality to add pre / post callback listeners
 * to specified transitions in the state machine and call them only when specified
 * transition happens.
 *
 *
 * @package Larium\Shop
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class EventTransition
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function beforeTransition($transition_name, $action)
    {
        $this->event_transition(
            $transition_name,
            $action,
            FiniteEvents::PRE_TRANSITION
        );
    }

    public function afterTransition($transition_name, $action)
    {
        $this->event_transition(
            $transition_name,
            $action,
            FiniteEvents::POST_TRANSITION
        );
    }

    /**
     * This will create listeners for each transition and tie the transition
     * name with that listener.
     *
     * @param string         $transition_name The name of transition to use the action.
     * @param Closure|array  $action          A callable to call with call_user_func_array function.
     * @param mixed          $event_name      Pre or Post transition event.
     * @access protected
     * @return void
     */
    protected function event_transition($transition_name, $action, $event_name)
    {
        $this->dispatcher->addListener(
            $event_name,
            function($event) use ($transition_name, $action) {
                if ($event->getTransition()->getName() == $transition_name) {
                    call_user_func_array($action, array($event));
                }
            }
        );
    }
}
