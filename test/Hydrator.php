<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use Larium\Shop\Common\Collection;
use Money\Money;

class Hydrator
{
    protected $class_name;
    protected $mapping;
    protected $storage;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;
        $this->mappings = include __DIR__ . '/data_mapping.conf.php';
        $this->storage = new \SplObjectStorage();
    }

    public function hydrate(array $data, $key=null, $class_name=null)
    {
        foreach ($this->storage as $item) {
            if ($this->storage[$item] == $key) {
                return $item;
            }
        }

        $class_name = $class_name ?: $this->class_name;

        $maps = isset($this->mappings[$this->class_name])
            ? $this->mappings[$this->class_name]
            : array();

        $class = new $class_name();

        foreach ($data as $key => $value) {

            $mutator = 'set' . $this->camelize($key);

            if (is_array($value) && array_key_exists($key, $maps)) {

                    $storage = new Collection();
                    foreach ($value as $k=>$v) {
                        $nest = $this->hydrate($v, null, $maps[$key]['class']);
                        $storage->add($nest);
                        if (isset($maps[$key]['inverse'])) {
                            $nest_mutator = 'set' . $this->camelize($maps[$key]['inverse']);
                            $nest->$nest_mutator($class);
                        }
                    }
                    $class->$mutator($storage);

            } else {
                if (in_array($key, ['unit_price', 'cost', 'amount', 'total_price'])) {
                    $value = Money::EUR($value * 100);
                }
                $class->$mutator($value);
            }
        }

        $this->storage->attach($class, $key);

        return $class;
    }

    private function camelize($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    private function underscore($camelCasedWord)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $camelCasedWord));
    }
}
