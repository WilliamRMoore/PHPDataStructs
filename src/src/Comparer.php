<?php

namespace DSL;

interface ComparerInterface
{
    function Compare($a, $b): int;
}

abstract class CompareResult
{
    const Lesser = -1;
    const Equals = 0;
    const Greater = 1;
}

class Comparer implements ComparerInterface
{
    public function Compare($a, $b): int
    {
        if ($a < $b) {
            return CompareResult::Lesser;
        }
        if ($a === $b) {
            return CompareResult::Equals;
        }
        return CompareResult::Greater;
    }
}
