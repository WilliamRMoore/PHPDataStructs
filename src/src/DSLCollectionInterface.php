<?php

namespace DSL;

interface DSLCollectionInterface extends \Iterator
{
    public function Count(): int;
    public function Clear(): void;
    public function ToArray(): array;
}
