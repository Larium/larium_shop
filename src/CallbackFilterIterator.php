<?php 

if (!class_exists('CallbackFilterIterator')) {    
    class CallbackFilterIterator extends FilterIterator {
        protected $callback;

        // "Closure" type hint should be "callable" in PHP 5.4
        public function __construct(Iterator $iterator, Closure $callback = null) {
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
}
