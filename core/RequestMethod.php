<?php

namespace core;

class RequestMethod
{
    public array $array;
    public function __construct($array)
    {
        $this->array = $array;
    }

    public function __get($name)
    {
        if(isset($this->array[$name]))
            return $this->array[$name];
        else
            return null;
    }
    public function getAll(): array
    {
        return $this->array;
    }
}