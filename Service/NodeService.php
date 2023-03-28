<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Node;

class NodeService
{
    public function newNode(Node $node, string $attribute, string $food): void
    {
        $actualFood = $node->getValue();

        $node->setValue($attribute);
        $node->setLeftChild(new Node($actualFood));
        $node->setRightChild(new Node($food));
    }
}