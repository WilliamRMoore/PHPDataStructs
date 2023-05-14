<?php

namespace DSL;

class DLinkedListNode
{
    public $Value;
    public ?DLinkedListNode $Next = null;
    public ?DLinkedListNode $Previous = null;

    public function __construct($value)
    {
        $this->Value = $value;
    }
}
