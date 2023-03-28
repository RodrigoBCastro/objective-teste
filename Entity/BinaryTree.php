<?php

declare(strict_types=1);

namespace App\Entity;

class BinaryTree
{
    private Node $root;

    public function add(?Node $parent, string $value, bool $answer)
    {
        $this->root = $this->change($parent, $value, $answer);
    }

    public function change(?Node $parent, string $value, bool $answer): Node
    {
        if ($parent === null) {
            return new Node($value);
        }

        if ($answer) {
            $parent->setRightChild($this->change($parent->getRightChild(), $value, $answer));
        } else {
            $parent->setLeftChild($this->change($parent->getLeftChild(), $value, $answer));
        }

        return $parent;
    }

    public function root(): ?Node
    {
        return $this->root;
    }
}