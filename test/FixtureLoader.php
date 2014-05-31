<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use Symfony\Component\Yaml\Parser;

class FixtureLoader
{
    protected $file;

    protected $data = array();

    public function __construct($file=null)
    {
        $this->file = $file;
    }

    protected function parse_file()
    {
        $yaml = new Parser();

        $this->data = $yaml->parse(file_get_contents(__DIR__ . '/fixtures/fixtures.yml'));
    }

    public function getData()
    {
        if (empty($this->data)) {
            $this->parse_file();
        }

        return $this->data;
    }

    public function instanceFor($class, $key, array $constructor=array())
    {
        $data = $this->getData();
        $attrs = $data[$key];

        return $this->init($class, $attrs, $constructor);
    }

    public function init($class, array $attributes, array $constructor=array())
    {

        if (!empty($constructor)) {
            $ref = new \ReflectionClass($class);
            $object = $ref->newInstanceArgs($constructor);
        } else {
            $object = new $class();
        }

        foreach ($attributes as $name=>$value) {
            $mutator = 'set' . self::camelize($name);
            $object->$mutator($value);
        }

        return $object;

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
