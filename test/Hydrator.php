<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

class Hydrator 
{
    protected $class_name;
    protected $mapping;

    public function __construct($class_name, array $mappings)
    {
        $this->class_name = $class_name;
        $this->mappings = $mappings;
    }

    public function hydrate(array $data, $class_name = null)
    {
        $class_name = $class_name ?: $this->class_name;
        
        $maps = $this->mappings[$this->class_name];
        
        $class = new $class_name();
        
        foreach ($data as $key => $value) {

            $mutator = 'set' . $this->camelize($key);
            
            if (is_array($value)) {
                
                if (array_key_exists($key, $maps)) {
                    $storage = new SplObjectStorage();
                    foreach ($value as $k=>$v) {
                        $nest = $this->hydrate($v, $maps[$key]['class']);
                        $storage->attach($nest);
                        if (isset($maps[$key]['inverse'])) {
                            $nest_mutator = 'set' . $this->camelize($maps[$key]['inverse']);
                            $nest->$nest_mutator($class);
                        }
                    }
                    $class->$mutator($storage);
                }
            } else {
                $class->$mutator($value);
            } 
        }
        
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
