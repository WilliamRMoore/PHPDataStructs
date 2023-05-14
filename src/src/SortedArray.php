<?php

namespace DSL;

use DSL\DSLCollectionInterface;
use DSL\Comparer;

class SortedArray
{
    private $Array = [];
    private ComparerInterface $Comparer;
    private int $Size = 0;

    public function __construct(ComparerInterface $comparer, array $values = null)
    {
        $this->Comparer = $comparer;

        if ($values) {
            $this->Array = $values;
            $this->Size = count($values);
            $this->Sort();
        }
    }

    public function NewSort(ComparerInterface $comparer)
    {
        $this->Comparer = $comparer;
        $this->Sort();
    }

    public function Add($value)
    {
        $this->Size++;
    }

    public function Count(): int
    {
        return $this->Size;
    }

    private function Sort()
    {
        usort($this->Array, function ($a, $b) {
            return $this->Comparer->Compare($a, $b);
        });
    }

    private function FindInsertIndex($value)
    {
    }

    private function BinarySearch(int $low, int $high, $find)
    {
        if ($this->Comparer->Compare($high, $low) <= 0) {
            return ($this->Comparer->Compare($find, $this->Array[$low])) === 1 ? ($low + 1) : $low;
        }
    }

    private function InsertSort()
    {
    }
}
