<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\StateMachine;

use Larium\StateMachine\Transition;
use Finite\StateMachine\StateMachineInterface;
use Finite\Loader\LoaderInterface;
use Finite\StatefulInterface;
use Finite\State\State;

class ArrayLoader implements LoaderInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = array_merge(
            array(
                'class'       => '',
                'states'      => array(),
                'transitions' => array(),
            ),
            $config
        );
    }

    /**
     * @{inheritdoc}
     */
    public function load(StateMachineInterface $stateMachine)
    {
        $this->loadStates($stateMachine);
        $this->loadTransitions($stateMachine);
    }

    /**
     * @{inheritdoc}
     */
    public function supports(StatefulInterface $object)
    {
        $reflection = new \ReflectionClass($this->config['class']);

        return $reflection->isInstance($object);
    }

    /**
     * @param StateMachineInterface $stateMachine
     */
    private function loadStates(StateMachineInterface $stateMachine)
    {
        foreach ($this->config['states'] as $state => $config) {
            $stateMachine->addState(new State($state, $config['type'], array(), $config['properties']));
        }
    }

    /**
     * @param StateMachineInterface $stateMachine
     */
    private function loadTransitions(StateMachineInterface $stateMachine)
    {
        foreach ($this->config['transitions'] as $transition => $config) {
            $do = isset($config['do']) ? $config['do'] : null;
            $if = isset($config['if']) ? $config['if'] : true;
            $stateMachine->addTransition(new Transition($transition, $config['from'], $config['to'], $do, $if));
        }
    }
}
