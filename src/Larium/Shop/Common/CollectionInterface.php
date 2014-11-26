<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Common;

use Closure;

interface CollectionInterface
{
    /**
     * Returns the first element of Collection.
     *
     * @access public
     * @return void
     */
    public function first();

    /**
     * Returns the last element of Collection.
     *
     * @access public
     * @return void
     */
    public function last();

    /**
     * Removes an element from the Collection.
     *
     * @param mixed    $element The element to remove.
     * @param Closure  $c       A callback for custom comparison of element to
     *                          remove.
     * @access public
     * @return boolean True if element removed successful or false if element
     *                 does not exist.
     */
    public function remove($element, Closure $c = null);

    /**
     * Adds a new element to collection.
     * Alias of ArrayIterator::append()
     *
     * @param mixed $element
     * @access public
     * @return boolean
     */
    public function add($element);

    /**
     * Check if Collection contains given element
     *
     * @param mixed    $element The element to check.
     * @param Closure  $c       A callback for custom comparison of element to
     *                          check.
     * @access public
     * @return mixed|boolean    If closurer isset return the element that is
     *                          stored in collection else return true or false.
     */
    public function contains($element, Closure $c = null);
}
