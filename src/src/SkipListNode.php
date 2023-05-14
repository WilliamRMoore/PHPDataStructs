<?php

namespace DSL;

class SkipListNode
{
    private $Key;
    private $Value;
    public ?array $ForwardPointer;

    public function __construct($key, $elem)
    {
        $this->Key = $key;
        $this->Value = $elem;
        $this->ForwardPointer = [];
    }

    public function Key()
    {
        return $this->Key;
    }

    public function Value()
    {
        return $this->Value;
    }
}
