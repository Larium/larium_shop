<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/*
 * This file is part of the Larium Shop package.
 *
 * (c) Andreas Kollaros <andreas@larium.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Larium\Shop\Common;

use Closure;
use ArrayIterator;

/**
 * Collection
 *
 * @uses ArrayIterator
 * @uses CollectionInterface
 * @author  Andreas Kollaros <andreas@larium.net>
 */
class Collection extends ArrayIterator implements CollectionInterface
{
    protected $elements_class;

    /**
     * Creates a new instance of Collection class.
     *
     * @param array  $array          An array of elements to manage.
     * @param string $elements_class The name of the class that collection can
     *                               manage.
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
        $this->validateElement($value);
        parent::append($value);

        return true;
    }

    /**
     * Selects th first element that satisfies callback and return it.
     *
     * @param Closure $c
     * @return mixed|false
     */
    public function select(Closure $c)
    {
        $key = $this->applyCallbackFilter($c);

        return $this->offsetGet($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element, Closure $c = null)
    {
        $this->validateElement($element);

        if (null === $c) {
            $key = array_search($element, $this->getArrayCopy(), true);

            if ($key !== false) {
                $this->offsetUnset($key);
                return true;
            }
        } else {
            if (false !== $key = $this->applyCallbackFilter($c)) {
                $this->offsetUnset($key);
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element, Closure $c = null)
    {
        if (null === $c) {
            return in_array($element, $this->getArrayCopy(), true);
        } else {
            if (false !== $key = $this->applyCallbackFilter($c)) {
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
        $this->validateElement($newval);
        parent::offsetSet($index, $newval);
    }

    private function validateElement($value)
    {
        if ($this->elements_class
            && !($value instanceof $this->elements_class)
        ) {
            throw new \Exception(sprintf('Collection can accept only %s instances', $this->elements_class));
        }
    }

    private function applyCallbackFilter(Closure $callback)
    {
        $elements = array_filter($this->getArrayCopy(), $callback);

        $keys = array_keys($elements);

        if (!empty($keys)) {
            return $keys[0];
        }

        return false;
    }
}
