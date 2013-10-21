<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Common;

class Collection extends \ArrayIterator implements CollectionInterface
{
    protected $elements_class;

    /**
     * Creates a new instance of Collection class.
     *
     * @param array  $array          An array of elements to manage.
     * @param int    $flags          Flags to control the behaviour of
     *                               the ArrayIterator
     * @param string $elements_class The name of the class that collection can
     *                               manage.
     * @access public
     * @return void
     */
    public function __construct($array = array(), $elements_class = null)
    {
        parent::__construct($array, 0);

        $this->elements_class = $elements_class;
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        $storage = $this->getArrayCopy();
        return reset($storage);
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        $storage = $this->getArrayCopy();
        return end($storage);
    }

    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        $this->append($element);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function append($value)
    {
        $this->validate_element($value);
        parent::append($value);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element, \Closure $c = null)
    {
        $this->validate_element($element);

        if (null === $c) {

            $key = array_search($element, $this->getArrayCopy(), true);

            if ($key !== false) {
                $this->offsetUnset($key);
                return true;
            }

        } else {

            if (false !== $key = $this->apply_filter_callback($c)) {
                $this->offsetUnset($key);
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element, \Closure $c = null)
    {
        if (null === $c) {
            return in_array($element, $this->getArrayCopy(), true);
        } else {
            if (false !== $key = $this->apply_filter_callback($c)) {
                return $this->offsetGet($key);
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $newval)
    {
        $this->validate_element($newval);
        parent::offsetSet($index, $newval);
    }

    private function validate_element($value)
    {
        if ($this->elements_class
            && !($value instanceof $this->elements_class)
        ) {
            throw new \Exception(sprintf('Collection can accept only %s instances', $this->elements_class));
        }
    }

    private function apply_filter_callback(\Closure $callback)
    {
        $elements = array_filter($this->getArrayCopy(), $callback);

        $keys = array_keys($elements);

        if (!empty($keys)) {
            return $keys[0];
        }

        return false;
    }


}
