<?php

namespace DSL;

use DSL\DSLCollectionInterface;

class Dictionary implements \ArrayAccess, DSLCollectionInterface
{
    private int $ArrPosition = 0;
    private array $DicArray = [];
    private int $Count = 0;

    public function __construct(array $values = [])
    {
        $this->DicArray = $values;
        $this->Count = count($values);
    }

    /**
     * @param &$outPut to be replaced
     */
    public function TryGetValue($key, &$outPut): bool
    {
        if ($this->ContainsKey($key)) {
            $outPut = $this->DicArray[$key];
            return true;
        }

        return false;
    }

    public function TryAdd($key, $value): bool
    {
        if ($this->ContainsKey($key)) {
            return false;
        }

        $this->Add($key, $value);
        $this->Count++;

        return true;
    }

    public function Add($key, $value): void
    {
        if ($this->ContainsKey($key)) {
            throw new \Exception("Key Already Exists!", 1);
        }

        $this->DicArray[$key] = $value;
        $this->Count++;
    }

    public function UpdateOrAdd($key, $value)
    {
        if (!$this->ContainsKey($key)) {
            $this->Count++;
        }

        $this->DicArray[$key] = $value;
    }

    public function Remove($key): bool
    {
        if (!$this->ContainsKey($key)) {
            return false;
        }

        unset($this->DicArray[$key]);
        $this->Count--;

        return true;
    }

    public function ContainsKey($key): bool
    {
        return array_key_exists($key, $this->DicArray);
    }

    public function ContainsValue(callable $delegate): bool
    {
        foreach ($this->DicArray as $value) {
            if ($delegate($value)) {
                return true;
            }
        }

        return false;
    }

    public function Keys(): array
    {
        return array_keys($this->DicArray);
    }

    public function Values(): array
    {
        return array_values($this->DicArray);
    }

    public function Count(): int
    {
        return $this->Count;
    }

    public function Clear(): void
    {
        $this->DicArray = array();
        $this->Count = 0;
    }

    public function ToArray(): array
    {
        $arr = $this->DicArray;
        return $arr;
    }

    // Array Access
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            throw new \Exception("Key required", 1);
        }

        $this->Add($offset, $value);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->DicArray[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->DicArray[$offset]) ? $this->DicArray[$offset] : null;
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->Remove($offset);
    }

    // Iterator Functions
    public function current(): mixed
    {
        return $this->DicArray[$this->Keys()[$this->ArrPosition]];
    }

    public function rewind(): void
    {
        $this->ArrPosition = 0;
    }

    public function key(): mixed
    {
        return $this->ArrPosition;
    }

    public function next(): void
    {
        $this->ArrPosition++;
    }

    public function valid(): bool
    {
        return isset($this->DicArray[$this->Keys()[$this->ArrPosition]]);
    }
}
