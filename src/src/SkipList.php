<?php

namespace DSL;

use DSL\SkipListNode;
use DSL\Comparer;
use DSL\DSLCollectionInterface;

class SkipList implements DSLCollectionInterface
{
    private ?SkipListNode $Head;
    public int $Level;
    private int $Size;
    private int $MaxLevels = 18; // log(100000) = 5 * 3.37 = 16.85 This is how many times 100000 can be divided by 2 before value becomes less than 1.
    private int $MaxSize = 200000; // Set due to php execution time limit.
    private ?SkipListNode $CurrentNode;
    private int $CurrentPosition;
    private ComparerInterface $Comparer;

    public function __construct(ComparerInterface $Comparer = null)
    {
        $this->Head = new SkipListNode(null, null, 0);
        $this->Level = -1;
        $this->Size = 0;
        if ($Comparer) {
            $this->Comparer = $Comparer;
        } else {
            $this->Comparer = new Comparer();
        }
    }

    public static function Create(ComparerInterface $Comparer = null)
    {
        return new self($Comparer);
    }

    public function SetMaxLevels(int $maxLevels): SkipList
    {
        if ($maxLevels <= 0) {
            throw new \Exception("MaxLevels must be 1 or grater", 1);
        }
        $this->MaxLevels = $maxLevels;
        return $this;
    }

    public function SetMaxSize($maxSize): SkipList
    {
        if ($maxSize <= 0) {
            throw new \Exception("MaxSize must be 1 or greater", 1);
        }
        $this->MaxSize = $maxSize;
        return $this;
    }

    public function Add($key, $elem)
    {
        if ($this->Size === $this->MaxSize) {
            throw new \Exception("Max Size!", 1);
        }

        $insertLevel = $this->RandomLevel();

        if ($insertLevel > $this->Level) {
            $this->AdjustLevel($insertLevel);
        }

        $update = array();
        $curNode = $this->Head;

        for ($level = $this->Level; $level >= 0; $level--) {
            while (isset($curNode->ForwardPointer[$level]) && $this->Comparer->Compare($curNode->ForwardPointer[$level]->Key(), $key) < 0) {
                $curNode = $curNode->ForwardPointer[$level];
            }
            $update[$level] = $curNode;
        }

        $curNode = new SkipListNode($key, $elem);

        for ($level = 0; $level <= $insertLevel; $level++) {
            if (isset($update[$level]->ForwardPointer[$level])) {
                $curNode->ForwardPointer[$level] = $update[$level]->ForwardPointer[$level]; // NewNode points to
            }

            $update[$level]->ForwardPointer[$level] = $curNode;
        }

        $this->Size++;
    }

    public function AddRange(array $values)
    {
        foreach ($values as $key => $value) {
            $this->Add($key, $value);
        }
    }

    public function Remove($key)
    {
        $update = array();
        $curNode = $this->Head;

        for ($level = $this->Level; $level >= 0; $level--) {
            while (isset($curNode->ForwardPointer[$level]) && $this->Comparer->Compare($curNode->ForwardPointer[$level]->Key(), $key) < 0) {
                $curNode = $curNode->ForwardPointer[$level];
            }
            $update[$level] = $curNode;
        }

        $curNode = $curNode->ForwardPointer[0];

        if ($curNode && $curNode->Key() === $key) {
            for ($level = 0; $level <= $this->Level; $level++) {
                if ($update[$level]->ForwardPointer[$level] != $curNode) {
                    break;
                }
                $update[$level]->ForwardPointer[$level] = $curNode->ForwardPointer[$level];
            }

            while ($this->Level > 0 && $this->Head->ForwardPointer[$this->Level] === null) {
                $this->Level--;
            }

            $this->Size--;

            return true;
        }

        return false;
    }

    public function Clear(): void
    {
        $this->Head = new SkipListNode(null, null, 0);
        $this->Level = -1;
        $this->Size = 0;
        $this->rewind();
    }

    public function ToArray(): array
    {
        $arr = [];
        foreach ($this as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }

    public function Count(): int
    {
        return $this->Size;
    }

    public function Find($key): mixed
    {
        $node = $this->FindNode($key);

        return $node ? $node->Value() : false;
    }

    public function current(): mixed
    {
        return $this->CurrentNode->Value();
    }

    public function key()
    {
        return $this->CurrentNode->Key();
    }

    public function next(): void
    {
        if (isset($this->CurrentNode->ForwardPointer[0])) {
            $this->CurrentNode = $this->CurrentNode->ForwardPointer[0];
        }
        $this->CurrentPosition++;
    }

    public function rewind(): void
    {
        $this->CurrentNode = $this->Head->ForwardPointer[0];
        $this->CurrentPosition = 0;
    }

    public function valid(): bool
    {
        return $this->CurrentPosition < $this->Size;
    }

    private function FindNode($key): SkipListNode | bool
    {
        $curNode = $this->Head;
        for ($level = $this->Level; $level >= 0; $level--) {
            while (isset($curNode->ForwardPointer[$level]) && $this->Comparer->Compare($curNode->ForwardPointer[$level]->Key(), $key) < 0) {
                $curNode = $curNode->ForwardPointer[$level];
            }
        }

        $curNode = $curNode->ForwardPointer[0];
        if ($curNode && $this->Comparer->Compare($curNode->Key(), $key) === 0) {
            return $curNode;
        }
        return false;
    }

    private function RandomLevel()
    {
        $levels = 0;
        // coin flip, land on 0 add a level, land on 1 return levels;
        while (mt_rand(0, 1) === 0 && $levels < $this->MaxLevels) {
            $levels++;
        }

        return $levels;
    }

    private function AdjustLevel(int $newLevel)
    {
        $temp = $this->Head;
        $this->Head = new SkipListNode(null, null, $newLevel);
        for ($i = 0; $i <= $this->Level; $i++) {
            $this->Head->ForwardPointer[$i] = $temp->ForwardPointer[$i] ?? null;
        }
        $this->Level = $newLevel;
    }
}
