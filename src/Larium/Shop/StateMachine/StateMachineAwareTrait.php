<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\StateMachine;

use Larium\Shop\StateMachine\ArrayLoader;
use Finite\StateMachine\StateMachine;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * StateMachineAwareTrait
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
trait StateMachineAwareTrait
{
    protected $state_machine;

    protected $event;

    public function getStateMachine()
    {
        if (null === $this->state_machine) {

            $data  = array(
                'class' => __CLASS__,
                'states' => $this->getStates(),
                'transitions' => $this->getTransitions()
            );

            $loader = new ArrayLoader($data);
            $dispatcher = new EventDispatcher();
            $this->state_machine = new StateMachine($this, $dispatcher);
            $loader->load($this->state_machine);

            $this->event = new EventTransition($dispatcher);

            $this->setupEvents();

            $this->state_machine->initialize();
        }

        return $this->state_machine;
    }
}
