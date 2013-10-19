<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\StateMachine;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * StateMachineAwareInterface
 *
 * @author  Andreas Kollaros <andreaskollaros@ymail.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
interface StateMachineAwareInterface
{
    public function getStateMachine();

    public function getStates();

    public function getTransitions();

    public function setupEvents();
}
