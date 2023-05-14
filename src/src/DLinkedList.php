<?php

namespace DSL;

use DSL\DLinkedListNode;
use DSL\DSLCollectionInterface;

class DoublyLinkedList implements DSLCollectionInterface
{
    private ?DLinkedListNode $Head = null;
    private ?DLinkedListNode $Tail = null;
    private int $CurrentPosition;
    private ?DLinkedListNode $CurrentNode;
    private int $Length = 0;

    public function AddLast($value): DLinkedListNode
    {
        $node = new DLinkedListNode($value);
        $this->AddLastNodeWithCheck($node);
        return $node;
    }

    public function AddLastNode(DLinkedListNode $node): DLinkedListNode
    {
        $this->AddLastNodeWithCheck($node, true);
        return $node;
    }

    private function AddLastNodeWithCheck(DLinkedListNode $node, bool $check = false): void
    {
        if ($check) {
            $this->ContainsNodeThrowsException($node);
        }

        if ($this->Length === 0) {
            $this->Head = $node;
            $this->Tail = $node;
            $this->Length++;
            return;
        }

        $this->Tail->Next = $node;
        $node->Previous = $this->Tail;
        $this->Tail = $node;
        $this->Length++;
        return;
    }

    public function AddFirst($value): DLinkedListNode
    {
        $node = new DLinkedListNode($value);
        $this->AddFirstNodeWithCheck($node);

        return $node;
    }

    public function AddFirstNode(DLinkedListNode $node): DLinkedListNode
    {
        $this->AddFirstNodeWithCheck($node, true);

        return $node;
    }

    private function AddFirstNodeWithCheck(DLinkedListNode $node, bool $check = false): void
    {
        if ($check) {
            $this->ContainsNodeThrowsException($node);
        }

        if ($this->Length === 0) {
            $this->Head = $node;
            $this->Tail = $node;
            $this->Length++;
        }

        $node->Next = $this->Head;
        $this->Head->Previous = $node;
        $this->Head = $node;
        $this->Length++;
    }

    public function AddAfter(DLinkedListNode $before, $value): DLinkedListNode
    {
        $node = new DLinkedListNode($value);
        $this->AddNodeAfterWithCheck($before, $node, true);

        return $node;
    }

    public function AddNodeAfter(DLinkedListNode $before, DLinkedListNode $after): DLinkedListNode
    {
        $this->AddNodeAfterWithCheck($before, $after, true, true);

        return $after;
    }

    private function AddNodeAfterWithCheck(DLinkedListNode $before, DLinkedListNode $after, bool $checkBefore = false, $checkAfter = false): void
    {
        if ($checkBefore) {
            $this->DoesNotContainNodeThrowsException($before);
        }

        if ($checkAfter) {
            $this->ContainsNodeThrowsException($after);
        }

        $after->Next = $before->Next;
        $before->Next = $after;
        $after->Previous = $before;

        if ($after->Next) {
            $after->Next->Previous = $after;
        } else {
            $this->Tail = $after;
        }

        $this->Length++;
    }

    public function AddBefore(DLinkedListNode $after, $value): DLinkedListNode
    {
        $node = new DLinkedListNode($value);
        $this->AddNodeBeforeWithCheck($after, $node, true);

        return $node;
    }

    public function AddNodeBefore(DLinkedListNode $after, DLinkedListNode $before): DLinkedListNode
    {
        $this->AddNodeBeforeWithCheck($after, $before, true, true);

        return $before;
    }

    private function AddNodeBeforeWithCheck(DLinkedListNode $after, DLinkedListNode $before, bool $checkAfter = false, bool $checkBefore = false)
    {
        if ($checkBefore) {
            $this->DoesNotContainNodeThrowsException($before);
        }

        if ($checkAfter) {
            $this->DoesNotContainNodeThrowsException($after);
        }

        $before->Next = $after;
        $before->Previous = $after->Previous;
        $after->Previous = $before;

        if ($before->Previous) {
            $before->Previous->Next = $before;
        } else {
            $this->Head = $before;
        }

        $this->Length++;
    }

    public function AddRange(array $arr)
    {
        foreach ($arr as $value) {
            $this->AddLast($value);
        }
    }

    public function RemoveLast(): void
    {
        $this->RemoveLastNode();
    }

    private function RemoveLastNode(): DLinkedListNode | null
    {
        if ($this->Length === 0) {
            return null;
        }

        $nodeToRemove = $this->Tail;

        if ($this->Length === 1) {
            $this->Head == null;
            $this->Tail == null;
            $this->Length--;

            return $nodeToRemove;
        }

        $this->Tail = $this->Tail->Previous;
        $this->Tail->Next = NULL;
        $nodeToRemove->Previous = null;
        $this->Length--;

        return $nodeToRemove;
    }

    public function RemoveFirst(): void
    {
        $this->RemoveFirstNode();
    }

    private function RemoveFirstNode(): DLinkedListNode | null
    {
        if ($this->Length === 0) {
            return null;
        }

        $nodeToRemove = $this->Head;

        if ($this->Length === 1) {
            $this->Head = null;
            $this->Tail = null;
            $this->Length--;

            return $nodeToRemove;
        }

        $this->Head = $this->Head->Next;
        $this->Head->Previous->Next = null;
        $this->Head->Previous = null;
        $nodeToRemove->Next = null;
        $this->Length--;

        return $nodeToRemove;
    }

    public function Remove($value)
    {
        $node = $this->FindFirst($value);
        if (!$node) {
            return false;
        }

        $this->RemoveNode($node);
        return true;
    }

    private function RemoveNode(DLinkedListNode $node)
    {
        if (!$node->Previous) {
            $this->RemoveFirstNode();
            return;
        }
        if (!$node->Next) {
            $this->RemoveLastNode();
            return;
        }
    }

    public function ValueAtIndex(int $index)
    {
        $var = $this->AtIndex($index)->Value;
        return $var;
    }

    public function NodeAtIndex(int $index): DLinkedListNode | null
    {
        return $this->AtIndex($index);
    }

    public function IndexOf(callable $delegate): int | null
    {
        if ($this->Length === 0) {
            return null;
        }

        $i = 0;

        $currentNode = $this->Head;

        while ($currentNode) {
            if ($delegate($currentNode->Value)) {
                return $i;
            }
            $i++;
            $currentNode = $currentNode->Next;
        }

        return null;
    }

    public function Contains(callable $delgate): bool
    {
        if ($this->Length === 0) {
            return false;
        }

        foreach ($this as $val) {
            if ($delgate($val)) {
                return true;
            }
        }

        return false;
    }

    public function Sort(callable $delegate)
    {
        if ($this->Length <= 0) {
            return;
        }

        $arr = $this->ToArray();
        usort($arr, $delegate);
        $this->Clear();
        $this->AddRange($arr);
    }

    public function ToArray(): array
    {
        $array = array();

        foreach ($this as $value) {
            $array[] = $value;
        }

        return $array;
    }

    public function Length(): int
    {
        return $this->Length;
    }

    public function current()
    {
        return $this->CurrentNode->Value;
    }

    public function key()
    {
        return $this->CurrentPosition;
    }

    public function next(): void
    {
        if ($this->CurrentNode->Next) {
            $this->CurrentNode = $this->CurrentNode->Next;
        }
        $this->CurrentPosition++;
    }

    public function rewind(): void
    {
        $this->CurrentNode = $this->Head;
        $this->CurrentPosition = 0;
    }

    public function valid(): bool
    {
        return $this->CurrentPosition < $this->Length;
    }

    private function AtIndex(int $index): DLinkedListNode|null
    {
        if ($this->Length == 0 || $index < 0 || $index >= $this->Length) {
            return null;
        }

        // If our index is less than have of the total length of the list, start at the head.
        if ($index < (($this->Length - 1) / 2)) {
            $i = 0;
            $currentNode = $this->Head;

            while ($i < $index) {
                $currentNode = $currentNode->Next;
                $i++;
            }

            return $currentNode;
        }

        // Otherwise, start at the tail.
        $i = $this->Length - 1;
        $currentNode = $this->Tail;

        while ($i > $index) {
            $currentNode = $currentNode->Previous;
            $i--;
        }

        return $currentNode;
    }

    private function DoesNotContainNodeThrowsException(DLinkedListNode $node)
    {
        if (!$this->ContainsNode($node)) {
            throw new \Exception("Invalid Operation, List does not contain Node!", 1);
        }
    }

    private function ContainsNodeThrowsException(DLinkedListNode $node)
    {
        if ($this->ContainsNode($node)) {
            throw new \Exception("Invalid Operation, List already contains Node!", 1);
        }
    }

    private function ContainsNode(DLinkedListNode $node): bool
    {
        foreach ($this->iterateList() as $value) {
            if ($value === $node) {
                return true;
            }
        }
        return false;
    }

    public function Clear(): void
    {
        while ($this->Length > 0) {
            $this->RemoveLast();
        }
    }

    public function Count(): int
    {
        return $this->Length;
    }

    public function FindFirst($find): DLinkedListNode | null
    {
        $node = $this->Head;

        foreach ($this->iterateList() as $node) {
            if ($node->Value == $find) {
                return $node;
            }
        }

        return null;
    }

    public function FindLast($find): DLinkedListNode | null
    {
        $last = null;

        foreach ($this->iterateList() as $node) {
            if ($node->Value == $find) {
                $last = $node;
            }
        }

        return $last;
    }

    private function iterateList()
    {
        $node = $this->Head;

        while ($node) {
            yield $node;
            $node = $node->Next;
        }
    }
}
