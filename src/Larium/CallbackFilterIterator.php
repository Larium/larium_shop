<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium;

class CallbackFilterIterator extends \FilterIterator
{

    protected $callback;

    public function __construct(\Iterator $iterator, \Closure $callback = null) {
        $this->callback = $callback;
        parent::__construct($iterator);
    }

    public function accept() {
        return call_user_func(
            $this->callback, 
            $this->current(), 
            $this->key(), 
            $this->getInnerIterator()
        );
    }
}
